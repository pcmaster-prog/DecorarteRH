<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\DelayRecord;
use App\Models\AbsenceRecord;
use App\Models\MealBreak;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceLog::with(['employee.person'])
            ->orderBy('date', 'desc');

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('date', [$request->from, $request->to]);
        }

        $logs = $query->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'employee_id' => $log->employee_id,
                    'employee_name' => $log->employee?->full_name,
                    'date' => $log->date,
                    'entry_time' => $log->entry_time?->format('H:i'),
                    'exit_time' => $log->exit_time?->format('H:i'),
                    'meal_start' => $log->meal_start_time?->format('H:i'),
                    'meal_end' => $log->meal_end_time?->format('H:i'),
                    'is_delay' => $log->is_delay,
                    'delay_minutes' => $log->delay_minutes,
                    'is_absence' => $log->is_absence,
                    'is_early_leave' => $log->is_early_leave,
                    'worked_hours' => $log->worked_hours,
                    'effective_hours' => $log->effective_hours,
                    'status' => $log->status,
                    'status_label' => $log->status_label,
                ];
            }),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    public function registerEntry(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today = now()->toDateString();

        $log = AttendanceLog::firstOrCreate(
            ['employee_id' => $employee->id, 'date' => $today],
            [
                'expected_entry' => $employee->shift?->entry_time,
                'expected_exit' => $employee->shift?->exit_time,
                'tolerance_minutes' => $employee->shift?->tolerance_minutes ?? 10,
            ]
        );

        if ($log->entry_time) {
            return response()->json(['message' => 'Ya registraste entrada hoy', 'log' => $log], 422);
        }

        $log->update([
            'entry_time' => now(),
        ]);

        // Verificar retardo
        $expectedTime = now()->setTimeFromTimeString($employee->shift?->entry_time ?? '08:30:00');
        $tolerance = $employee->shift?->tolerance_minutes ?? 10;

        if (now()->greaterThan($expectedTime->copy()->addMinutes($tolerance))) {
            $delayMinutes = $expectedTime->diffInMinutes(now());
            $log->update([
                'is_delay' => true,
                'delay_minutes' => $delayMinutes,
            ]);

            DelayRecord::create([
                'employee_id' => $employee->id,
                'attendance_log_id' => $log->id,
                'date' => $today,
                'expected_time' => $expectedTime,
                'actual_time' => now(),
                'delay_minutes' => $delayMinutes,
                'tolerance_minutes' => $tolerance,
            ]);

            // Verificar si hay 3 retardos para convertir a falta
            $recentDelays = DelayRecord::where('employee_id', $employee->id)
                ->where('is_converted_to_absence', false)
                ->count();

            if ($recentDelays >= 3) {
                $this->convertDelaysToAbsence($employee);
            }
        }

        return response()->json([
            'message' => 'Entrada registrada correctamente',
            'log' => $log->fresh(),
        ]);
    }

    public function registerExit(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today = now()->toDateString();

        $log = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if (!$log) {
            return response()->json(['message' => 'No hay registro de entrada para hoy'], 404);
        }

        if ($log->exit_time) {
            return response()->json(['message' => 'Ya registraste salida hoy'], 422);
        }

        $log->update([
            'exit_time' => now(),
        ]);

        // Calcular horas trabajadas
        $workedMinutes = $log->entry_time->diffInMinutes(now());
        $mealMinutes = 0;
        if ($log->meal_start_time && $log->meal_end_time) {
            $mealMinutes = $log->meal_start_time->diffInMinutes($log->meal_end_time);
        }
        $effectiveMinutes = $workedMinutes - $mealMinutes;

        $log->update([
            'worked_hours' => round($workedMinutes / 60, 2),
            'effective_hours' => round($effectiveMinutes / 60, 2),
        ]);

        return response()->json([
            'message' => 'Salida registrada correctamente',
            'log' => $log->fresh(),
        ]);
    }

    public function startMeal(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $employee = Employee::findOrFail($request->employee_id);
        $today = now()->toDateString();

        $log = AttendanceLog::where('employee_id', $employee->id)->whereDate('date', $today)->first();
        if (!$log) return response()->json(['message' => 'No hay registro de entrada'], 404);

        $meal = MealBreak::create([
            'employee_id' => $employee->id,
            'attendance_log_id' => $log->id,
            'started_at' => now(),
            'expected_duration_minutes' => $employee->shift?->meal_duration_minutes ?? 30,
        ]);

        $log->update(['meal_start_time' => now()]);

        return response()->json(['message' => 'Comida iniciada', 'meal' => $meal]);
    }

    public function endMeal(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $employee = Employee::findOrFail($request->employee_id);
        $today = now()->toDateString();

        $meal = MealBreak::where('employee_id', $employee->id)
            ->whereDate('started_at', $today)
            ->whereNull('ended_at')
            ->first();

        if (!$meal) return response()->json(['message' => 'No hay comida activa'], 404);

        $actualDuration = $meal->started_at->diffInMinutes(now());
        $isExceeded = $actualDuration > $meal->expected_duration_minutes;
        $exceededMinutes = $isExceeded ? $actualDuration - $meal->expected_duration_minutes : 0;

        $meal->update([
            'ended_at' => now(),
            'actual_duration_minutes' => $actualDuration,
            'is_exceeded' => $isExceeded,
            'exceeded_minutes' => $exceededMinutes,
        ]);

        $log = AttendanceLog::where('employee_id', $employee->id)->whereDate('date', $today)->first();
        if ($log) $log->update(['meal_end_time' => now()]);

        return response()->json([
            'message' => 'Comida finalizada',
            'meal' => $meal->fresh(),
            'is_exceeded' => $isExceeded,
            'exceeded_minutes' => $exceededMinutes,
        ]);
    }

    public function todayStatus(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $employee = Employee::findOrFail($request->employee_id);
        $today = now()->toDateString();

        $log = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->with(['mealBreaks'])
            ->first();

        if (!$log) {
            return response()->json([
                'status' => 'not_registered',
                'message' => 'Sin registro de asistencia hoy',
            ]);
        }

        $activeMeal = $log->mealBreaks()->whereNull('ended_at')->first();

        return response()->json([
            'status' => $log->status,
            'status_label' => $log->status_label,
            'entry_time' => $log->entry_time?->format('H:i'),
            'exit_time' => $log->exit_time?->format('H:i'),
            'meal_active' => !!$activeMeal,
            'meal_started_at' => $activeMeal?->started_at?->format('H:i'),
            'is_delay' => $log->is_delay,
            'delay_minutes' => $log->delay_minutes,
            'worked_hours' => $log->worked_hours,
            'effective_hours' => $log->effective_hours,
        ]);
    }

    private function convertDelaysToAbsence($employee)
    {
        $delays = DelayRecord::where('employee_id', $employee->id)
            ->where('is_converted_to_absence', false)
            ->orderBy('date')
            ->take(3)
            ->get();

        if ($delays->count() >= 3) {
            $absence = AbsenceRecord::create([
                'employee_id' => $employee->id,
                'date' => now()->toDateString(),
                'type' => 'converted_delays',
                'reason' => 'Conversión automática de 3 retardos acumulados a 1 falta.',
                'is_justified' => false,
                'converted_from_delays' => true,
                'registered_by' => auth()->id() ?? 1,
            ]);

            foreach ($delays as $delay) {
                $delay->update([
                    'is_converted_to_absence' => true,
                    'converted_absence_id' => $absence->id,
                ]);
            }

            // Verificar si hay 3 faltas para suspensión
            $totalAbsences = AbsenceRecord::where('employee_id', $employee->id)->count();
            if ($totalAbsences >= 3) {
                $this->triggerSuspension($employee);
            }
        }
    }

    private function triggerSuspension($employee)
    {
        $employee->person->update(['status' => Person::STATUS_ACCOUNT_SUSPENDED]);
        $employee->user?->update(['is_active' => false]);

        \App\Models\AccountSuspension::create([
            'employee_id' => $employee->id,
            'triggered_by' => auth()->id() ?? 1,
            'trigger_reason' => 'Acumulación de 3 faltas (incluyendo conversiones de retardos).',
            'absence_count' => AbsenceRecord::where('employee_id', $employee->id)->count(),
            'delay_count' => DelayRecord::where('employee_id', $employee->id)->count(),
            'suspended_at' => now(),
            'is_active' => true,
        ]);

        \App\Models\AdministrativeWarning::create([
            'employee_id' => $employee->id,
            'type' => 'suspension',
            'reason' => 'Acumulación de 3 faltas',
            'description' => 'Cuenta suspendida preventivamente por acumulación de faltas.',
            'issued_by' => auth()->id() ?? 1,
            'issued_at' => now(),
            'severity' => 'critical',
            'requires_acknowledgement' => true,
            'is_active' => true,
        ]);
    }
}

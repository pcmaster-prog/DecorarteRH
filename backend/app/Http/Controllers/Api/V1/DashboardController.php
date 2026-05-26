<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Models\TaskAssignment;
use App\Models\AccountSuspension;
use App\Models\VacationRequest;
use App\Models\DelayRecord;
use App\Models\AbsenceRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $kpis = [
            'employees_active' => Employee::active()->count(),
            'employees_suspended' => Employee::whereHas('person', function ($q) {
                $q->where('status', \App\Models\Person::STATUS_ACCOUNT_SUSPENDED);
            })->count(),
            'tasks_in_progress' => TaskAssignment::where('status', 'in_progress')->count(),
            'tasks_completed_today' => TaskAssignment::where('status', 'completed')
                ->whereDate('completed_at', $today)->count(),
            'delays_this_week' => DelayRecord::whereBetween('date', [$weekStart, $weekEnd])->count(),
            'absences_this_week' => AbsenceRecord::whereBetween('date', [$weekStart, $weekEnd])->count(),
            'vacation_requests_pending' => VacationRequest::whereIn('status', ['sent', 'supervisor_review', 'manager_review'])->count(),
            'attendance_today' => AttendanceLog::whereDate('date', $today)->count(),
        ];

        // Operación en vivo
        $liveOperation = AttendanceLog::with(['employee.person', 'employee.position', 'employee.department'])
            ->whereDate('date', $today)
            ->whereNull('exit_time')
            ->get()
            ->map(function ($log) {
                return [
                    'employee_id' => $log->employee_id,
                    'name' => $log->employee?->full_name,
                    'photo' => $log->employee?->person?->photo_url,
                    'position' => $log->employee?->position?->display_name,
                    'department' => $log->employee?->department?->display_name,
                    'department_color' => $log->employee?->department?->color,
                    'status' => $log->meal_start_time && !$log->meal_end_time ? 'meal' : ($log->status === 'in_progress' ? 'working' : 'available'),
                    'entry_time' => $log->entry_time?->format('H:i'),
                    'current_task' => $log->employee?->taskAssignments()
                        ->where('status', 'in_progress')
                        ->with('task')
                        ->first()?->task?->title,
                ];
            });

        // Alertas críticas
        $alerts = [];

        $suspensions = AccountSuspension::where('is_active', true)
            ->with('employee.person')
            ->get();
        foreach ($suspensions as $suspension) {
            $alerts[] = [
                'type' => 'suspension',
                'severity' => 'critical',
                'message' => "{$suspension->employee?->full_name} tiene cuenta suspendida preventivamente",
                'employee_id' => $suspension->employee_id,
            ];
        }

        $pendingVacations = VacationRequest::whereIn('status', ['sent', 'supervisor_review'])
            ->with('employee.person')
            ->get();
        foreach ($pendingVacations as $vacation) {
            $alerts[] = [
                'type' => 'vacation',
                'severity' => 'medium',
                'message' => "Solicitud de vacaciones pendiente: {$vacation->employee?->full_name}",
                'employee_id' => $vacation->employee_id,
            ];
        }

        return response()->json([
            'kpis' => $kpis,
            'live_operation' => $liveOperation,
            'alerts' => $alerts,
        ]);
    }

    public function employee(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $employee = Employee::with(['person', 'position', 'department', 'supervisor.person'])
            ->findOrFail($request->employee_id);

        $today = now()->toDateString();

        $attendance = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        $tasks = TaskAssignment::with('task')
            ->where('employee_id', $employee->id)
            ->whereDate('assigned_at', $today)
            ->get();

        $vacationBalance = $employee->vacationBalances()->first();

        return response()->json([
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'position' => $employee->position?->display_name,
                'department' => $employee->department?->display_name,
                'supervisor' => $employee->supervisor?->full_name,
                'photo' => $employee->person?->photo_url,
            ],
            'attendance' => $attendance ? [
                'status' => $attendance->status,
                'status_label' => $attendance->status_label,
                'entry_time' => $attendance->entry_time?->format('H:i'),
                'exit_time' => $attendance->exit_time?->format('H:i'),
                'is_delay' => $attendance->is_delay,
            ] : null,
            'tasks_today' => [
                'total' => $tasks->count(),
                'pending' => $tasks->where('status', 'pending')->count(),
                'in_progress' => $tasks->where('status', 'in_progress')->count(),
                'completed' => $tasks->whereIn('status', ['completed', 'approved'])->count(),
                'list' => $tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->task?->title,
                        'status' => $task->status,
                        'priority' => $task->effective_priority,
                    ];
                }),
            ],
            'vacation' => $vacationBalance ? [
                'days_available' => $vacationBalance->days_available,
                'days_taken' => $vacationBalance->days_taken,
                'days_pending' => $vacationBalance->days_pending,
            ] : null,
            'seniority' => $employee->getSeniorityDisplayAttribute(),
        ]);
    }
}

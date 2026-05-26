<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeSeniorityHourRecord;
use Illuminate\Http\Request;

class SeniorityController extends Controller
{
    public function show($employeeId)
    {
        $employee = Employee::with('seniorityHours')->findOrFail($employeeId);
        $record = $employee->seniorityHours;

        if (!$record) {
            return response()->json(['message' => 'No hay registro de antigüedad'], 404);
        }

        return response()->json([
            'data' => [
                'employee_id' => $employee->id,
                'employee_name' => $employee->full_name,
                'total_historical_hours' => $record->total_historical_hours,
                'hours_per_workday' => $record->hours_per_workday_config,
                'workdays_per_week' => $record->workdays_per_week_config,
                'equivalent_days' => $record->equivalent_days,
                'equivalent_weeks' => $record->equivalent_weeks,
                'equivalent_months' => $record->equivalent_months,
                'equivalent_years' => $record->equivalent_years,
                'human_readable' => $record->human_readable_seniority,
                'recognized_label' => $record->recognized_seniority_label,
                'real_hire_date' => $record->real_hire_date,
                'platform_registered_at' => $record->platform_registered_at,
                'status' => $record->status,
                'impacts' => [
                    'vacations' => $record->impacts_vacations,
                    'christmas_bonus' => $record->impacts_christmas_bonus,
                    'profit_sharing' => $record->impacts_profit_sharing,
                    'severance' => $record->impacts_severance,
                    'recommendation_letter' => $record->impacts_recommendation_letter,
                ],
                'validated_by' => $record->validatedBy?->person?->full_name,
                'validated_at' => $record->validated_at,
                'notes' => $record->notes,
            ],
        ]);
    }

    public function store(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $request->validate([
            'total_historical_hours' => 'required|numeric|min:0',
            'hours_per_workday_config' => 'nullable|numeric|min:1',
            'workdays_per_week_config' => 'nullable|integer|min:1|max:7',
            'weeks_per_month_config' => 'nullable|numeric|min:1',
            'months_per_year_config' => 'nullable|integer|min:1',
            'real_hire_date' => 'nullable|date',
            'calculation_method' => 'nullable|string|in:hours,date,manual,validated',
            'impacts_vacations' => 'nullable|boolean',
            'impacts_christmas_bonus' => 'nullable|boolean',
            'impacts_profit_sharing' => 'nullable|boolean',
            'impacts_severance' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $record = EmployeeSeniorityHourRecord::updateOrCreate(
            ['employee_id' => $employeeId],
            [
                'total_historical_hours' => $request->total_historical_hours,
                'hours_per_workday_config' => $request->hours_per_workday_config ?? 8,
                'workdays_per_week_config' => $request->workdays_per_week_config ?? 6,
                'weeks_per_month_config' => $request->weeks_per_month_config ?? 4.33,
                'months_per_year_config' => $request->months_per_year_config ?? 12,
                'real_hire_date' => $request->real_hire_date,
                'platform_registered_at' => now(),
                'calculation_method' => $request->calculation_method ?? 'hours',
                'impacts_vacations' => $request->impacts_vacations ?? true,
                'impacts_christmas_bonus' => $request->impacts_christmas_bonus ?? true,
                'impacts_profit_sharing' => $request->impacts_profit_sharing ?? true,
                'impacts_severance' => $request->impacts_severance ?? true,
                'impacts_recommendation_letter' => true,
                'status' => 'draft',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]
        );

        $record->calculateSeniority();
        $record->save();

        return response()->json([
            'message' => 'Antigüedad registrada',
            'data' => $record->fresh(),
        ], 201);
    }

    public function validateRecord(Request $request, $employeeId)
    {
        $record = EmployeeSeniorityHourRecord::where('employee_id', $employeeId)->firstOrFail();

        if ($record->status === 'validated') {
            return response()->json(['message' => 'La antigüedad ya está validada'], 422);
        }

        $record->update([
            'status' => 'validated',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        return response()->json(['message' => 'Antigüedad validada correctamente']);
    }

    public function reopen(Request $request, $employeeId)
    {
        $request->validate(['reason' => 'required|string|min:10']);

        $record = EmployeeSeniorityHourRecord::where('employee_id', $employeeId)->firstOrFail();

        if ($record->status !== 'validated') {
            return response()->json(['message' => 'Solo se puede reabrir antigüedad validada'], 422);
        }

        $record->update([
            'status' => 'reopened',
            'reopened_by' => auth()->id(),
            'reopened_at' => now(),
            'reopen_reason' => $request->reason,
        ]);

        return response()->json(['message' => 'Antigüedad reabierta para edición']);
    }
}

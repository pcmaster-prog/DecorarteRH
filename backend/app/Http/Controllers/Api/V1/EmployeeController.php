<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['person', 'position', 'department', 'supervisor.person', 'shift'])
            ->active();

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('supervisor_id')) {
            $query->where('supervisor_id', $request->supervisor_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => $employees->map(function ($employee) {
                return $this->formatEmployee($employee);
            }),
            'meta' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    public function show($id)
    {
        $employee = Employee::with([
            'person', 'position', 'department', 'supervisor.person', 'manager.person',
            'shift', 'employeeType', 'seniorityHours', 'vacationBalances',
            'historicalBenefits', 'attendanceLogs', 'delayRecords', 'absenceRecords',
            'taskAssignments.task', 'routineAssignments.routine', 'documents',
            'contracts', 'statusHistories', 'currentContract'
        ])->findOrFail($id);

        return response()->json([
            'data' => $this->formatEmployeeDetail($employee),
        ]);
    }

    public function kardex($id)
    {
        $employee = Employee::with([
            'person', 'position', 'department', 'supervisor.person', 'manager.person',
            'shift', 'employeeType', 'seniorityHours', 'vacationBalances',
            'historicalBenefits', 'attendanceLogs', 'delayRecords', 'absenceRecords',
            'taskAssignments.task', 'routineAssignments.routine', 'documents',
            'contracts', 'statusHistories', 'currentContract',
            'accountSuspensions', 'administrativeWarnings'
        ])->findOrFail($id);

        return response()->json([
            'data' => [
                'employee' => $this->formatEmployeeDetail($employee),
                'seniority' => $employee->seniorityHours ? [
                    'total_historical_hours' => $employee->seniorityHours->total_historical_hours,
                    'equivalent_years' => $employee->seniorityHours->equivalent_years,
                    'equivalent_months' => $employee->seniorityHours->equivalent_months,
                    'equivalent_days' => $employee->seniorityHours->equivalent_days,
                    'human_readable' => $employee->seniorityHours->human_readable_seniority,
                    'recognized_label' => $employee->seniorityHours->recognized_seniority_label,
                    'status' => $employee->seniorityHours->status,
                    'impacts_vacations' => $employee->seniorityHours->impacts_vacations,
                    'impacts_christmas_bonus' => $employee->seniorityHours->impacts_christmas_bonus,
                    'impacts_profit_sharing' => $employee->seniorityHours->impacts_profit_sharing,
                    'impacts_severance' => $employee->seniorityHours->impacts_severance,
                    'validated_at' => $employee->seniorityHours->validated_at,
                    'validated_by' => $employee->seniorityHours->validatedBy?->person?->full_name,
                ] : null,
                'vacation_balances' => $employee->vacationBalances->map(function ($balance) {
                    return [
                        'period_year' => $balance->period_year,
                        'days_generated' => $balance->days_generated,
                        'days_taken' => $balance->days_taken,
                        'days_pending' => $balance->days_pending,
                        'days_available' => $balance->days_available,
                        'vacation_bonus_generated' => $balance->vacation_bonus_generated,
                        'vacation_bonus_paid' => $balance->vacation_bonus_paid,
                        'vacation_bonus_pending' => $balance->vacation_bonus_pending,
                        'deadline_date' => $balance->deadline_date,
                    ];
                }),
                'historical_benefits' => $employee->historicalBenefits->map(function ($benefit) {
                    return [
                        'benefit_type' => $benefit->benefit_type,
                        'period_year' => $benefit->period_year,
                        'status' => $benefit->status,
                        'status_label' => $benefit->status_label,
                        'status_color' => $benefit->status_color,
                        'amount_paid' => $benefit->amount_paid,
                        'payment_date' => $benefit->payment_date,
                        'published_to_employee' => $benefit->published_to_employee,
                    ];
                }),
                'attendance_summary' => [
                    'total_delays' => $employee->getTotalDelaysAttribute(),
                    'total_absences' => $employee->getTotalAbsencesAttribute(),
                    'converted_absences' => $employee->getConvertedAbsencesAttribute(),
                ],
                'suspensions' => $employee->accountSuspensions->map(function ($suspension) {
                    return [
                        'suspended_at' => $suspension->suspended_at,
                        'reason' => $suspension->trigger_reason,
                        'absence_count' => $suspension->absence_count,
                        'is_active' => $suspension->is_active,
                    ];
                }),
                'warnings' => $employee->administrativeWarnings->map(function ($warning) {
                    return [
                        'type' => $warning->type,
                        'reason' => $warning->reason,
                        'severity' => $warning->severity,
                        'severity_color' => $warning->severity_color,
                        'issued_at' => $warning->issued_at,
                        'is_acknowledged' => $warning->isAcknowledged(),
                    ];
                }),
            ],
        ]);
    }

    private function formatEmployee($employee): array
    {
        return [
            'id' => $employee->id,
            'employee_number' => $employee->employee_number,
            'full_name' => $employee->full_name,
            'email' => $employee->person?->email,
            'phone' => $employee->person?->phone,
            'photo_url' => $employee->person?->photo_url,
            'position' => $employee->position?->display_name,
            'department' => $employee->department?->display_name,
            'department_color' => $employee->department?->color,
            'supervisor' => $employee->supervisor?->full_name,
            'shift' => $employee->shift?->display_name,
            'hire_date' => $employee->hire_date,
            'seniority' => $employee->getSeniorityDisplayAttribute(),
            'status' => $employee->person?->status,
            'status_label' => $employee->person?->status_label,
            'status_color' => $employee->person?->status_color,
            'is_active' => $employee->is_active,
        ];
    }

    private function formatEmployeeDetail($employee): array
    {
        return array_merge($this->formatEmployee($employee), [
            'curp' => $employee->person?->curp,
            'rfc' => $employee->person?->rfc,
            'nss' => $employee->person?->nss,
            'date_of_birth' => $employee->person?->date_of_birth,
            'gender' => $employee->person?->gender,
            'address' => $employee->person?->address,
            'city' => $employee->person?->city,
            'state' => $employee->person?->state,
            'emergency_contact' => $employee->person ? [
                'name' => $employee->person->emergency_contact_name,
                'phone' => $employee->person->emergency_contact_phone,
                'relation' => $employee->person->emergency_contact_relation,
            ] : null,
            'base_salary' => $employee->base_salary,
            'salary_type' => $employee->salary_type,
            'rest_day' => $employee->rest_day,
            'work_schedule' => $employee->work_schedule,
            'platform_registered_at' => $employee->platform_registered_at,
            'manager' => $employee->manager?->full_name,
            'employee_type' => $employee->employeeType?->display_name,
        ]);
    }
}

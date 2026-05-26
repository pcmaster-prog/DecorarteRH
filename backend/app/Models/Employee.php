<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'employee_number',
        'employee_type_id',
        'position_id',
        'department_id',
        'supervisor_id',
        'manager_id',
        'shift_id',
        'hire_date',
        'platform_registered_at',
        'termination_date',
        'termination_reason',
        'base_salary',
        'salary_type',
        'work_schedule',
        'rest_day',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'platform_registered_at' => 'datetime',
        'termination_date' => 'date',
        'base_salary' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const SALARY_TYPE_MONTHLY = 'monthly';
    const SALARY_TYPE_WEEKLY = 'weekly';
    const SALARY_TYPE_HOURLY = 'hourly';
    const SALARY_TYPE_DAILY = 'daily';

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function employeeType(): BelongsTo
    {
        return $this->belongsTo(EmployeeType::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function seniorityHours(): HasOne
    {
        return $this->hasOne(EmployeeSeniorityHourRecord::class);
    }

    public function historicalBenefits(): HasMany
    {
        return $this->hasMany(EmployeeHistoricalBenefit::class);
    }

    public function vacationBalances(): HasMany
    {
        return $this->hasMany(EmployeeVacationBalance::class);
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function delayRecords(): HasMany
    {
        return $this->hasMany(DelayRecord::class);
    }

    public function absenceRecords(): HasMany
    {
        return $this->hasMany(AbsenceRecord::class);
    }

    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function routineAssignments(): HasMany
    {
        return $this->hasMany(RoutineAssignment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(EmployeeStatusHistory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function currentContract(): HasOne
    {
        return $this->hasOne(Contract::class)->where('is_active', true)->latest();
    }

    public function getFullNameAttribute(): string
    {
        return $this->person?->full_name ?? 'Sin nombre';
    }

    public function getSeniorityDisplayAttribute(): string
    {
        $record = $this->seniorityHours;
        if (!$record) {
            $years = $this->hire_date ? now()->diffInYears($this->hire_date) : 0;
            $months = $this->hire_date ? now()->diffInMonths($this->hire_date) % 12 : 0;
            return "{$years} años y {$months} meses";
        }
        return $record->recognized_seniority_label ?? $record->human_readable_seniority ?? 'Pendiente';
    }

    public function getTotalDelaysAttribute(): int
    {
        return $this->delayRecords()->count();
    }

    public function getTotalAbsencesAttribute(): int
    {
        return $this->absenceRecords()->count();
    }

    public function getConvertedAbsencesAttribute(): int
    {
        // 3 retardos = 1 falta
        $extraAbsences = intdiv($this->getTotalDelaysAttribute(), 3);
        return $this->getTotalAbsencesAttribute() + $extraAbsences;
    }

    public function isSuspended(): bool
    {
        return $this->person?->status === Person::STATUS_ACCOUNT_SUSPENDED;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereHas('person', function ($q) {
                $q->whereIn('status', [
                    Person::STATUS_EMPLOYEE_ACTIVE,
                    Person::STATUS_EMPLOYEE_HOURLY,
                    Person::STATUS_EMPLOYEE_TRAINING,
                    Person::STATUS_HIRED_ACTIVE,
                ]);
            });
    }

    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeBySupervisor($query, int $supervisorId)
    {
        return $query->where('supervisor_id', $supervisorId);
    }
}

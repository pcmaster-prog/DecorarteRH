<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSeniorityHourRecord extends Model
{
    use HasFactory;

    protected $table = 'employee_seniority_hour_records';

    protected $fillable = [
        'employee_id',
        'total_historical_hours',
        'hours_per_workday_config',
        'workdays_per_week_config',
        'weeks_per_month_config',
        'months_per_year_config',
        'calculation_method',
        'equivalent_days',
        'equivalent_weeks',
        'equivalent_months',
        'equivalent_years',
        'human_readable_seniority',
        'recognized_seniority_label',
        'real_hire_date',
        'platform_registered_at',
        'impacts_vacations',
        'impacts_christmas_bonus',
        'impacts_profit_sharing',
        'impacts_severance',
        'impacts_recommendation_letter',
        'status',
        'notes',
        'evidence_document_id',
        'created_by',
        'validated_by',
        'validated_at',
        'reopened_by',
        'reopened_at',
        'reopen_reason',
    ];

    protected $casts = [
        'total_historical_hours' => 'decimal:2',
        'hours_per_workday_config' => 'decimal:2',
        'workdays_per_week_config' => 'integer',
        'weeks_per_month_config' => 'decimal:2',
        'months_per_year_config' => 'integer',
        'equivalent_days' => 'decimal:2',
        'equivalent_weeks' => 'decimal:2',
        'equivalent_months' => 'decimal:2',
        'equivalent_years' => 'decimal:2',
        'real_hire_date' => 'date',
        'platform_registered_at' => 'datetime',
        'impacts_vacations' => 'boolean',
        'impacts_christmas_bonus' => 'boolean',
        'impacts_profit_sharing' => 'boolean',
        'impacts_severance' => 'boolean',
        'impacts_recommendation_letter' => 'boolean',
        'validated_at' => 'datetime',
        'reopened_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_VALIDATED = 'validated';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_REOPENED = 'reopened';

    const METHOD_HOURS = 'hours';
    const METHOD_DATE = 'date';
    const METHOD_MANUAL = 'manual';
    const METHOD_VALIDATED = 'validated';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function reopenedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reopened_by');
    }

    public function calculateSeniority(): void
    {
        $hours = $this->total_historical_hours;
        $hoursPerDay = $this->hours_per_workday_config ?? 8;
        $daysPerWeek = $this->workdays_per_week_config ?? 6;
        $weeksPerMonth = $this->weeks_per_month_config ?? 4.33;
        $monthsPerYear = $this->months_per_year_config ?? 12;

        $this->equivalent_days = $hours / $hoursPerDay;
        $this->equivalent_weeks = $this->equivalent_days / $daysPerWeek;
        $this->equivalent_months = $this->equivalent_weeks / $weeksPerMonth;
        $this->equivalent_years = $this->equivalent_months / $monthsPerYear;

        // Formato humano legible
        $totalDays = (int) $this->equivalent_days;
        $years = intdiv($totalDays, 365);
        $remainingDays = $totalDays % 365;
        $months = intdiv($remainingDays, 30);
        $days = $remainingDays % 30;
        $remainingHours = $hours - ($totalDays * $hoursPerDay);

        $parts = [];
        if ($years > 0) $parts[] = "{$years} año" . ($years > 1 ? 's' : '');
        if ($months > 0) $parts[] = "{$months} mes" . ($months > 1 ? 'es' : '');
        if ($days > 0) $parts[] = "{$days} día" . ($days > 1 ? 's' : '');
        if ($remainingHours > 0) $parts[] = "{$remainingHours} hora" . ($remainingHours > 1 ? 's' : '');

        $this->human_readable_seniority = empty($parts) ? '0 horas' : implode(', ', $parts);

        // Antigüedad visible simplificada
        $visibleParts = [];
        if ($years > 0) $visibleParts[] = "{$years} año" . ($years > 1 ? 's' : '');
        if ($months > 0) $visibleParts[] = "{$months} mes" . ($months > 1 ? 'es' : '');
        $this->recognized_seniority_label = empty($visibleParts) 
            ? ($days > 0 ? "{$days} días" : 'Menos de 1 mes')
            : implode(' y ', $visibleParts);
    }

    public function isValidated(): bool
    {
        return $this->status === self::STATUS_VALIDATED;
    }

    public function canEdit(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_REOPENED]);
    }
}

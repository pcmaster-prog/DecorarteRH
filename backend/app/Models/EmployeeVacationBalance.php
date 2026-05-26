<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeVacationBalance extends Model
{
    use HasFactory;

    protected $table = 'employee_vacation_balances';

    protected $fillable = [
        'employee_id',
        'period_year',
        'days_generated',
        'days_taken',
        'days_paid',
        'days_pending',
        'days_expired',
        'vacation_bonus_generated',
        'vacation_bonus_paid',
        'vacation_bonus_pending',
        'period_start',
        'period_end',
        'deadline_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'days_generated' => 'integer',
        'days_taken' => 'integer',
        'days_paid' => 'integer',
        'days_pending' => 'integer',
        'days_expired' => 'integer',
        'vacation_bonus_generated' => 'decimal:2',
        'vacation_bonus_paid' => 'decimal:2',
        'vacation_bonus_pending' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'deadline_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(VacationRequest::class, 'balance_id');
    }

    public function getDaysAvailableAttribute(): int
    {
        return max(0, $this->days_generated - $this->days_taken - $this->days_paid - $this->days_expired);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->deadline_date && now()->greaterThan($this->deadline_date);
    }
}

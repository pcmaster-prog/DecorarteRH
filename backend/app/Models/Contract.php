<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'contract_type',
        'contract_number',
        'start_date',
        'end_date',
        'salary',
        'position_id',
        'department_id',
        'shift_id',
        'trial_period_days',
        'trial_end_date',
        'is_trial_approved',
        'trial_approved_by',
        'trial_approved_at',
        'template_id',
        'content',
        'signed_at',
        'signed_by',
        'is_active',
        'terminated_at',
        'termination_reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'trial_end_date' => 'date',
        'trial_approved_at' => 'datetime',
        'signed_at' => 'datetime',
        'terminated_at' => 'datetime',
        'salary' => 'decimal:2',
        'trial_period_days' => 'integer',
        'is_trial_approved' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_INDEFINITE = 'indefinite';
    const TYPE_FIXED = 'fixed';
    const TYPE_TRIAL = 'trial';
    const TYPE_TRAINING = 'training';
    const TYPE_HOURLY = 'hourly';
    const TYPE_PART_TIME = 'part_time';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class, 'template_id');
    }

    public function trialApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trial_approved_by');
    }

    public function signedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ContractVersion::class);
    }

    public function isInTrial(): bool
    {
        if (!$this->trial_end_date) return false;
        return now()->lessThanOrEqualTo($this->trial_end_date) && !$this->is_trial_approved;
    }

    public function isExpired(): bool
    {
        if (!$this->end_date) return false;
        return now()->greaterThan($this->end_date);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSuspension extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'triggered_by',
        'trigger_reason',
        'absence_count',
        'delay_count',
        'suspended_at',
        'reactivated_at',
        'reactivated_by',
        'reactivation_reason',
        'reactivation_notes',
        'conversation_summary',
        'is_active',
    ];

    protected $casts = [
        'absence_count' => 'integer',
        'delay_count' => 'integer',
        'suspended_at' => 'datetime',
        'reactivated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function reactivatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reactivated_by');
    }

    public function isCurrentlySuspended(): bool
    {
        return $this->is_active && !$this->reactivated_at;
    }
}

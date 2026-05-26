<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'category',
        'estimated_minutes',
        'requires_evidence',
        'evidence_type',
        'evidence_instructions',
        'is_recurring',
        'recurrence_pattern',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'estimated_minutes' => 'integer',
        'requires_evidence' => 'boolean',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

    const PRIORITY_CRITICAL = 'critical';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_LOW = 'low';
    const PRIORITY_FLEXIBLE = 'flexible';

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_PAUSED = 'paused';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_COMPLETED = 'completed';
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REQUIRES_CORRECTION = 'requires_correction';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REASSIGNED = 'reassigned';
    const STATUS_EXPIRED = 'expired';

    public function assignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPriorityColorAttribute(): string
    {
        $colors = [
            self::PRIORITY_CRITICAL => 'red',
            self::PRIORITY_HIGH => 'orange',
            self::PRIORITY_MEDIUM => 'yellow',
            self::PRIORITY_LOW => 'blue',
            self::PRIORITY_FLEXIBLE => 'gray',
        ];
        return $colors[$this->priority] ?? 'gray';
    }
}

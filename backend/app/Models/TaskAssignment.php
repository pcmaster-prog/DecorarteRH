<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'employee_id',
        'assigned_by',
        'assigned_at',
        'due_date',
        'due_time',
        'started_at',
        'completed_at',
        'status',
        'priority_override',
        'notes',
        'rejection_reason',
        'evidence_url',
        'evidence_description',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'due_date' => 'date',
        'due_time' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(TaskStatusLog::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(TaskEvidence::class);
    }

    public function getEffectivePriorityAttribute(): string
    {
        return $this->priority_override ?? $this->task?->priority ?? 'medium';
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date) return false;
        $due = $this->due_time ? $this->due_date->copy()->setTimeFrom($this->due_time) : $this->due_date->endOfDay();
        return now()->greaterThan($due) && !in_array($this->status, ['completed', 'approved', 'cancelled']);
    }

    public function getDurationMinutesAttribute(): ?int
    {
        if (!$this->started_at || !$this->completed_at) return null;
        return $this->started_at->diffInMinutes($this->completed_at);
    }
}

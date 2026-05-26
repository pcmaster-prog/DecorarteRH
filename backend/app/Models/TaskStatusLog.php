<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskStatusLog extends Model
{
    use HasFactory;

    protected $table = 'task_status_logs';

    protected $fillable = [
        'task_assignment_id',
        'from_status',
        'to_status',
        'changed_by',
        'changed_at',
        'notes',
        'ip_address',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

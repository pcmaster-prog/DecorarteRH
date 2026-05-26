<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutineProgress extends Model
{
    use HasFactory;

    protected $table = 'routine_progress';

    protected $fillable = [
        'routine_assignment_id',
        'routine_task_id',
        'task_assignment_id',
        'status',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function routineAssignment(): BelongsTo
    {
        return $this->belongsTo(RoutineAssignment::class);
    }

    public function routineTask(): BelongsTo
    {
        return $this->belongsTo(RoutineTask::class);
    }

    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(TaskAssignment::class);
    }
}

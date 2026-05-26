<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutineTask extends Model
{
    use HasFactory;

    protected $table = 'routine_tasks';

    protected $fillable = [
        'routine_id',
        'task_id',
        'order',
        'is_required',
        'notes',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_required' => 'boolean',
    ];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}

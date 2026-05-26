<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';

    protected $fillable = [
        'task_assignment_id',
        'user_id',
        'comment',
        'is_internal',
        'created_at',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

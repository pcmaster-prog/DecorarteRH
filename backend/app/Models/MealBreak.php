<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealBreak extends Model
{
    use HasFactory;

    protected $table = 'meal_breaks';

    protected $fillable = [
        'employee_id',
        'attendance_log_id',
        'started_at',
        'ended_at',
        'expected_duration_minutes',
        'actual_duration_minutes',
        'is_exceeded',
        'exceeded_minutes',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'expected_duration_minutes' => 'integer',
        'actual_duration_minutes' => 'integer',
        'is_exceeded' => 'boolean',
        'exceeded_minutes' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendanceLog(): BelongsTo
    {
        return $this->belongsTo(AttendanceLog::class);
    }

    public function getDurationAttribute(): int
    {
        if (!$this->started_at || !$this->ended_at) return 0;
        return $this->started_at->diffInMinutes($this->ended_at);
    }

    public function isActive(): bool
    {
        return $this->started_at && !$this->ended_at;
    }
}

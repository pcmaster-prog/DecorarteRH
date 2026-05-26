<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeWorkloadMetric extends Model
{
    use HasFactory;

    protected $table = 'employee_workload_metrics';

    protected $fillable = [
        'employee_id',
        'date',
        'tasks_assigned',
        'tasks_completed',
        'tasks_overdue',
        'routines_assigned',
        'routines_completed',
        'workload_score',
        'efficiency_score',
        'punctuality_score',
        'attendance_score',
        'learning_score',
        'reliability_score',
        'overall_score',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'tasks_assigned' => 'integer',
        'tasks_completed' => 'integer',
        'tasks_overdue' => 'integer',
        'routines_assigned' => 'integer',
        'routines_completed' => 'integer',
        'workload_score' => 'decimal:2',
        'efficiency_score' => 'decimal:2',
        'punctuality_score' => 'decimal:2',
        'attendance_score' => 'decimal:2',
        'learning_score' => 'decimal:2',
        'reliability_score' => 'decimal:2',
        'overall_score' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getPerformanceGradeAttribute(): string
    {
        $score = $this->overall_score;
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }

    public function getPerformanceColorAttribute(): string
    {
        $score = $this->overall_score;
        if ($score >= 90) return 'green';
        if ($score >= 80) return 'teal';
        if ($score >= 70) return 'yellow';
        if ($score >= 60) return 'orange';
        return 'red';
    }
}

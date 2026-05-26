<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'entry_time',
        'meal_start_time',
        'meal_end_time',
        'exit_time',
        'expected_entry',
        'expected_exit',
        'tolerance_minutes',
        'is_delay',
        'delay_minutes',
        'is_absence',
        'absence_type',
        'is_early_leave',
        'early_leave_minutes',
        'is_holiday',
        'is_overtime',
        'overtime_minutes',
        'worked_hours',
        'effective_hours',
        'notes',
        'registered_by',
        'device',
        'location',
        'ip_address',
    ];

    protected $casts = [
        'date' => 'date',
        'entry_time' => 'datetime',
        'meal_start_time' => 'datetime',
        'meal_end_time' => 'datetime',
        'exit_time' => 'datetime',
        'expected_entry' => 'datetime',
        'expected_exit' => 'datetime',
        'is_delay' => 'boolean',
        'delay_minutes' => 'integer',
        'is_absence' => 'boolean',
        'is_early_leave' => 'boolean',
        'early_leave_minutes' => 'integer',
        'is_holiday' => 'boolean',
        'is_overtime' => 'boolean',
        'overtime_minutes' => 'integer',
        'worked_hours' => 'decimal:2',
        'effective_hours' => 'decimal:2',
    ];

    const ABSENCE_TYPE_UNJUSTIFIED = 'unjustified';
    const ABSENCE_TYPE_JUSTIFIED = 'justified';
    const ABSENCE_TYPE_PERMISSION = 'permission';
    const ABSENCE_TYPE_VACATION = 'vacation';
    const ABSENCE_TYPE_HOLIDAY = 'holiday';
    const ABSENCE_TYPE_SUSPENSION = 'suspension';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_absence) return 'absence';
        if ($this->is_delay) return 'delay';
        if ($this->is_early_leave) return 'early_leave';
        if (!$this->entry_time) return 'not_registered';
        if ($this->entry_time && !$this->exit_time) return 'in_progress';
        return 'complete';
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'absence' => 'Falta',
            'delay' => 'Retardo',
            'early_leave' => 'Salida anticipada',
            'not_registered' => 'Sin registrar',
            'in_progress' => 'En jornada',
            'complete' => 'Completo',
        ];
        return $labels[$this->status] ?? 'Desconocido';
    }
}

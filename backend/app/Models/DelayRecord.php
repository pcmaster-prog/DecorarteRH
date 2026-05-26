<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DelayRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_log_id',
        'date',
        'expected_time',
        'actual_time',
        'delay_minutes',
        'tolerance_minutes',
        'is_converted_to_absence',
        'converted_absence_id',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'expected_time' => 'datetime',
        'actual_time' => 'datetime',
        'delay_minutes' => 'integer',
        'tolerance_minutes' => 'integer',
        'is_converted_to_absence' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendanceLog(): BelongsTo
    {
        return $this->belongsTo(AttendanceLog::class);
    }

    public function convertedAbsence(): BelongsTo
    {
        return $this->belongsTo(AbsenceRecord::class, 'converted_absence_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbsenceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'type',
        'reason',
        'is_justified',
        'justification_document_id',
        'converted_from_delays',
        'notes',
        'registered_by',
    ];

    protected $casts = [
        'date' => 'date',
        'is_justified' => 'boolean',
        'converted_from_delays' => 'boolean',
    ];

    const TYPE_UNJUSTIFIED = 'unjustified';
    const TYPE_JUSTIFIED = 'justified';
    const TYPE_PERMISSION = 'permission';
    const TYPE_VACATION = 'vacation';
    const TYPE_SUSPENSION = 'suspension';
    const TYPE_CONVERTED_DELAYS = 'converted_delays';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function convertedDelays(): HasMany
    {
        return $this->hasMany(DelayRecord::class, 'converted_absence_id');
    }
}

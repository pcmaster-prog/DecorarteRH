<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdministrativeWarning extends Model
{
    use HasFactory;

    protected $table = 'administrative_warnings';

    protected $fillable = [
        'employee_id',
        'type',
        'reason',
        'description',
        'issued_by',
        'issued_at',
        'severity',
        'requires_acknowledgement',
        'acknowledged_at',
        'acknowledged_by',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'requires_acknowledgement' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_DELAY = 'delay';
    const TYPE_ABSENCE = 'absence';
    const TYPE_SUSPENSION = 'suspension';
    const TYPE_BEHAVIOR = 'behavior';
    const TYPE_PERFORMANCE = 'performance';
    const TYPE_ATTENDANCE = 'attendance';

    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function acknowledgedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function acknowledgements(): HasMany
    {
        return $this->hasMany(AdministrativeWarningAcknowledgement::class, 'warning_id');
    }

    public function isAcknowledged(): bool
    {
        return $this->acknowledged_at !== null;
    }

    public function getSeverityColorAttribute(): string
    {
        $colors = [
            self::SEVERITY_LOW => 'blue',
            self::SEVERITY_MEDIUM => 'yellow',
            self::SEVERITY_HIGH => 'orange',
            self::SEVERITY_CRITICAL => 'red',
        ];
        return $colors[$this->severity] ?? 'gray';
    }
}

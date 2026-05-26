<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VacationRequest extends Model
{
    use HasFactory;

    protected $table = 'vacation_requests';

    protected $fillable = [
        'employee_id',
        'balance_id',
        'start_date',
        'end_date',
        'days_requested',
        'reason',
        'status',
        'is_high_season',
        'requires_special_approval',
        'requested_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_requested' => 'integer',
        'is_high_season' => 'boolean',
        'requires_special_approval' => 'boolean',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_SUPERVISOR_REVIEW = 'supervisor_review';
    const STATUS_MANAGER_REVIEW = 'manager_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REQUIRES_ADJUSTMENT = 'requires_adjustment';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_TAKEN = 'taken';
    const STATUS_CLOSED = 'closed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function balance(): BelongsTo
    {
        return $this->belongsTo(EmployeeVacationBalance::class, 'balance_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(VacationRequestApproval::class, 'request_id');
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_DRAFT => 'Borrador',
            self::STATUS_SENT => 'Enviada',
            self::STATUS_SUPERVISOR_REVIEW => 'En revisión por supervisor',
            self::STATUS_MANAGER_REVIEW => 'En revisión por gerente',
            self::STATUS_APPROVED => 'Aprobada',
            self::STATUS_REJECTED => 'Rechazada',
            self::STATUS_REQUIRES_ADJUSTMENT => 'Requiere ajuste',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_TAKEN => 'Tomada',
            self::STATUS_CLOSED => 'Cerrada',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_DRAFT => 'gray',
            self::STATUS_SENT => 'blue',
            self::STATUS_SUPERVISOR_REVIEW => 'yellow',
            self::STATUS_MANAGER_REVIEW => 'orange',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_REQUIRES_ADJUSTMENT => 'purple',
            self::STATUS_CANCELLED => 'gray',
            self::STATUS_TAKEN => 'teal',
            self::STATUS_CLOSED => 'gray',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_SENT, self::STATUS_SUPERVISOR_REVIEW, self::STATUS_MANAGER_REVIEW]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeHistoricalBenefit extends Model
{
    use HasFactory;

    protected $table = 'employee_historical_benefits';

    protected $fillable = [
        'employee_id',
        'benefit_type',
        'period_year',
        'period_start',
        'period_end',
        'days_generated',
        'days_taken',
        'days_paid',
        'hours_paid',
        'amount_paid',
        'payment_date',
        'status',
        'notes',
        'document_id',
        'created_by',
        'validated_by',
        'published_to_employee',
        'published_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'days_generated' => 'integer',
        'days_taken' => 'integer',
        'days_paid' => 'integer',
        'hours_paid' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
        'published_to_employee' => 'boolean',
        'published_at' => 'datetime',
    ];

    const TYPE_VACATION = 'vacation';
    const TYPE_VACATION_BONUS = 'vacation_bonus';
    const TYPE_CHRISTMAS_BONUS = 'christmas_bonus';
    const TYPE_PROFIT_SHARING = 'profit_sharing';
    const TYPE_BONUS = 'bonus';
    const TYPE_GRATUITY = 'gratuity';
    const TYPE_HOLIDAY = 'holiday';
    const TYPE_OVERTIME = 'overtime';
    const TYPE_REST_DAY = 'rest_day';
    const TYPE_OTHER = 'other';

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PARTIALLY_PAID = 'partially_paid';
    const STATUS_NOT_APPLICABLE = 'not_applicable';
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_OBSERVED = 'observed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PAID => 'Pagado',
            self::STATUS_PARTIALLY_PAID => 'Parcialmente pagado',
            self::STATUS_NOT_APPLICABLE => 'No aplica',
            self::STATUS_IN_REVIEW => 'En revisión',
            self::STATUS_OBSERVED => 'Observado',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PAID => 'green',
            self::STATUS_PARTIALLY_PAID => 'blue',
            self::STATUS_NOT_APPLICABLE => 'gray',
            self::STATUS_IN_REVIEW => 'orange',
            self::STATUS_OBSERVED => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }
}

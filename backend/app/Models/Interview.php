<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'interview_type',
        'scheduled_at',
        'completed_at',
        'interviewer_id',
        'location',
        'status',
        'score',
        'notes',
        'recommendation',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    const TYPE_INITIAL = 'initial';
    const TYPE_TECHNICAL = 'technical';
    const TYPE_BEHAVIORAL = 'behavioral';
    const TYPE_FINAL = 'final';
    const TYPE_ADMIN = 'admin';

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RESCHEDULED = 'rescheduled';
    const STATUS_NO_SHOW = 'no_show';

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

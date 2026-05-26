<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'job_vacancy_id',
        'source',
        'google_id',
        'application_date',
        'evaluation_score',
        'interview_date',
        'trial_start_date',
        'trial_end_date',
        'trial_evaluation',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'application_date' => 'date',
        'interview_date' => 'datetime',
        'trial_start_date' => 'date',
        'trial_end_date' => 'date',
        'evaluation_score' => 'decimal:2',
        'trial_evaluation' => 'decimal:2',
    ];

    const SOURCE_PORTAL = 'portal';
    const SOURCE_REFERRAL = 'referral';
    const SOURCE_SOCIAL_MEDIA = 'social_media';
    const SOURCE_JOB_BOARD = 'job_board';
    const SOURCE_WALK_IN = 'walk_in';

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(CandidateEvaluation::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->person?->full_name ?? 'Sin nombre';
    }
}

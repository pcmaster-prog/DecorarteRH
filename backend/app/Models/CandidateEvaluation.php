<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateEvaluation extends Model
{
    use HasFactory;

    protected $table = 'candidate_evaluations';

    protected $fillable = [
        'candidate_id',
        'evaluation_type',
        'score',
        'max_score',
        'percentage',
        'passed',
        'evaluated_by',
        'evaluated_at',
        'notes',
        'answers',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'passed' => 'boolean',
        'evaluated_at' => 'datetime',
        'answers' => 'json',
    ];

    const TYPE_INITIAL = 'initial';
    const TYPE_INTERVIEW = 'interview';
    const TYPE_PRACTICAL = 'practical';
    const TYPE_FINAL = 'final';

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function evaluatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}

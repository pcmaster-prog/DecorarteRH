<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    use HasFactory;

    protected $table = 'job_vacancies';

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'position_id',
        'department_id',
        'location',
        'salary_range_min',
        'salary_range_max',
        'vacancies_count',
        'filled_count',
        'status',
        'published_at',
        'expires_at',
        'is_public',
        'created_by',
    ];

    protected $casts = [
        'salary_range_min' => 'decimal:2',
        'salary_range_max' => 'decimal:2',
        'vacancies_count' => 'integer',
        'filled_count' => 'integer',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_PAUSED = 'paused';
    const STATUS_CLOSED = 'closed';
    const STATUS_FILLED = 'filled';

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true)
            ->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PUBLISHED, self::STATUS_PAUSED]);
    }

    public function getRemainingVacanciesAttribute(): int
    {
        return max(0, $this->vacancies_count - $this->filled_count);
    }
}

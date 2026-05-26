<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
        'frequency',
        'day_of_week',
        'department_id',
        'position_id',
        'estimated_duration_minutes',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'estimated_duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    const CATEGORY_OPENING = 'opening';
    const CATEGORY_CLOSING = 'closing';
    const CATEGORY_DAILY = 'daily';
    const CATEGORY_WEEKLY = 'weekly';
    const CATEGORY_MONTHLY = 'monthly';
    const CATEGORY_AREA = 'area';
    const CATEGORY_INVENTORY = 'inventory';
    const CATEGORY_CLEANING = 'cleaning';

    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_BIWEEKLY = 'biweekly';
    const FREQUENCY_MONTHLY = 'monthly';

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(RoutineTask::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(RoutineAssignment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

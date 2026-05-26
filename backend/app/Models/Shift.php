<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'entry_time',
        'exit_time',
        'meal_duration_minutes',
        'tolerance_minutes',
        'days_per_week',
        'is_night_shift',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'entry_time' => 'datetime:H:i',
        'exit_time' => 'datetime:H:i',
        'meal_duration_minutes' => 'integer',
        'tolerance_minutes' => 'integer',
        'days_per_week' => 'integer',
        'is_night_shift' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function getTotalHoursAttribute(): float
    {
        if (!$this->entry_time || !$this->exit_time) return 0;
        $entry = \Carbon\Carbon::parse($this->entry_time);
        $exit = \Carbon\Carbon::parse($this->exit_time);
        $totalMinutes = $entry->diffInMinutes($exit);
        $effectiveMinutes = $totalMinutes - ($this->meal_duration_minutes ?? 0);
        return round($effectiveMinutes / 60, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

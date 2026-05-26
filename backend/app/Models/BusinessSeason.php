<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSeason extends Model
{
    use HasFactory;

    protected $table = 'business_seasons';

    protected $fillable = [
        'name',
        'display_name',
        'type',
        'start_date',
        'end_date',
        'color',
        'is_vacation_blocked',
        'requires_special_approval',
        'max_vacation_employees_per_day',
        'max_vacation_employees_per_area',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_vacation_blocked' => 'boolean',
        'requires_special_approval' => 'boolean',
        'max_vacation_employees_per_day' => 'integer',
        'max_vacation_employees_per_area' => 'integer',
        'is_active' => 'boolean',
    ];

    const TYPE_LOW = 'low';
    const TYPE_REGULAR = 'regular';
    const TYPE_HIGH = 'high';
    const TYPE_BLOCKED = 'blocked';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function isCurrent(): bool
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            self::TYPE_LOW => 'Temporada baja',
            self::TYPE_REGULAR => 'Temporada regular',
            self::TYPE_HIGH => 'Temporada alta',
            self::TYPE_BLOCKED => 'Temporada bloqueada',
        ];
        return $labels[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        $colors = [
            self::TYPE_LOW => 'green',
            self::TYPE_REGULAR => 'blue',
            self::TYPE_HIGH => 'orange',
            self::TYPE_BLOCKED => 'red',
        ];
        return $colors[$this->type] ?? 'gray';
    }
}

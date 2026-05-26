<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'type',
        'is_recurring',
        'year',
        'is_paid',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
        'year' => 'integer',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_FIXED = 'fixed';
    const TYPE_MOVABLE = 'movable';
    const TYPE_ELECTORAL = 'electoral';
    const TYPE_LOCAL = 'local';
    const TYPE_SPECIAL = 'special';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where(function ($q) use ($year) {
            $q->where('year', $year)
              ->orWhere('is_recurring', true);
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())->orderBy('date');
    }
}

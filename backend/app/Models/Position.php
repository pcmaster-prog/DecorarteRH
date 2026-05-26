<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'department_id',
        'level',
        'is_supervisor',
        'is_manager',
        'is_director',
        'base_salary_min',
        'base_salary_max',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_supervisor' => 'boolean',
        'is_manager' => 'boolean',
        'is_director' => 'boolean',
        'base_salary_min' => 'decimal:2',
        'base_salary_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

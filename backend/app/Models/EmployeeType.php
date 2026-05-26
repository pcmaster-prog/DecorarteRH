<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeType extends Model
{
    use HasFactory;

    protected $table = 'employee_types';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_hourly',
        'is_salaried',
        'is_contract',
        'is_intern',
        'is_active',
    ];

    protected $casts = [
        'is_hourly' => 'boolean',
        'is_salaried' => 'boolean',
        'is_contract' => 'boolean',
        'is_intern' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

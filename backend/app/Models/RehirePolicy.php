<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RehirePolicy extends Model
{
    use HasFactory;

    protected $table = 'rehire_policies';

    protected $fillable = [
        'name',
        'min_months_wait',
        'max_rehire_attempts',
        'applies_to_recommended',
        'applies_to_not_recommended',
        'applies_to_terminated',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'min_months_wait' => 'integer',
        'max_rehire_attempts' => 'integer',
        'applies_to_recommended' => 'boolean',
        'applies_to_not_recommended' => 'boolean',
        'applies_to_terminated' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractTemplate extends Model
{
    use HasFactory;

    protected $table = 'contract_templates';

    protected $fillable = [
        'name',
        'display_name',
        'contract_type',
        'content',
        'variables',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'variables' => 'json',
        'is_active' => 'boolean',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function render(array $data): string
    {
        $content = $this->content;
        foreach ($data as $key => $value) {
            $content = str_replace("{{{$key}}}", $value, $content);
        }
        return $content;
    }
}

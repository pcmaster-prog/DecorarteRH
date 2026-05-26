<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalRule extends Model
{
    use HasFactory;

    protected $table = 'legal_rules';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
        'rule_key',
        'value',
        'value_type',
        'effective_from',
        'effective_to',
        'is_active',
        'version',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'value' => 'json',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    const CATEGORY_WORKDAY = 'workday';
    const CATEGORY_WORKWEEK = 'workweek';
    const CATEGORY_OVERTIME = 'overtime';
    const CATEGORY_HOLIDAY = 'holiday';
    const CATEGORY_VACATION = 'vacation';
    const CATEGORY_VACATION_BONUS = 'vacation_bonus';
    const CATEGORY_CHRISTMAS_BONUS = 'christmas_bonus';
    const CATEGORY_PROFIT_SHARING = 'profit_sharing';
    const CATEGORY_SENIORITY = 'seniority';
    const CATEGORY_DELAY = 'delay';
    const CATEGORY_ABSENCE = 'absence';
    const CATEGORY_SUSPENSION = 'suspension';
    const CATEGORY_WARNING = 'warning';
    const CATEGORY_PERMISSION = 'permission';
    const CATEGORY_MEAL = 'meal';
    const CATEGORY_PAYROLL = 'payroll';
    const CATEGORY_REHIRE = 'rehire';
    const CATEGORY_CONTRACT = 'contract';
    const CATEGORY_TRIAL = 'trial';
    const CATEGORY_TRAINING = 'training';

    public function versions(): HasMany
    {
        return $this->hasMany(LegalRuleVersion::class, 'rule_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', now());
            });
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', now());
            });
    }

    public static function getValue(string $ruleKey, $default = null)
    {
        $rule = self::where('rule_key', $ruleKey)
            ->where('is_active', true)
            ->current()
            ->first();

        return $rule?->value ?? $default;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalRuleVersion extends Model
{
    use HasFactory;

    protected $table = 'legal_rule_versions';

    protected $fillable = [
        'rule_id',
        'version',
        'value',
        'effective_from',
        'effective_to',
        'created_by',
        'change_reason',
    ];

    protected $casts = [
        'value' => 'json',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'version' => 'integer',
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(LegalRule::class, 'rule_id');
    }
}

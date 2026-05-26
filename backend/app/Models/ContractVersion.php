<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractVersion extends Model
{
    use HasFactory;

    protected $table = 'contract_versions';

    protected $fillable = [
        'contract_id',
        'version',
        'content',
        'changes',
        'created_by',
    ];

    protected $casts = [
        'changes' => 'json',
        'version' => 'integer',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

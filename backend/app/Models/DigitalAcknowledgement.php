<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalAcknowledgement extends Model
{
    use HasFactory;

    protected $table = 'digital_acknowledgements';

    protected $fillable = [
        'acknowledgeable_type',
        'acknowledgeable_id',
        'user_id',
        'acknowledged_at',
        'ip_address',
        'device',
        'user_agent',
        'signature_image',
        'notes',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function acknowledgeable()
    {
        return $this->morphTo();
    }
}

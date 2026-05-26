<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministrativeWarningAcknowledgement extends Model
{
    use HasFactory;

    protected $table = 'administrative_warning_acknowledgements';

    protected $fillable = [
        'warning_id',
        'acknowledged_by',
        'acknowledged_at',
        'ip_address',
        'device',
        'notes',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];

    public function warning(): BelongsTo
    {
        return $this->belongsTo(AdministrativeWarning::class, 'warning_id');
    }

    public function acknowledgedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }
}

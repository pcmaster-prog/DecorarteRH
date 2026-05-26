<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryPermissionGrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_name',
        'scope',
        'granted_by',
        'granted_at',
        'expires_at',
        'reason',
        'is_active',
    ];

    protected $casts = [
        'granted_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('expires_at', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at < now();
    }
}

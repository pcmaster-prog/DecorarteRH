<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'is_critical',
        'requires_approval',
        'is_system',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'requires_approval' => 'boolean',
        'is_system' => 'boolean',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permission_user')
            ->withPivot('scope', 'granted_at', 'granted_by', 'expires_at', 'is_restricted')
            ->withTimestamps();
    }

    public function accessProfiles(): BelongsToMany
    {
        return $this->belongsToMany(AccessProfile::class, 'access_profile_permissions')
            ->withTimestamps();
    }

    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }
}

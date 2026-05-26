<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'person_id',
        'email',
        'password',
        'google_id',
        'avatar',
        'is_active',
        'last_login_at',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Niveles jerárquicos
    const LEVEL_ADMIN = 100;
    const LEVEL_MANAGER = 80;
    const LEVEL_HR = 75;
    const LEVEL_SUPERVISOR = 60;
    const LEVEL_EMPLOYEE = 30;
    const LEVEL_EMPLOYEE_HOURLY = 25;
    const LEVEL_PROSPECT = 20;
    const LEVEL_CANDIDATE = 10;
    const LEVEL_EX_EMPLOYEE = 5;

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot('is_primary', 'granted_at', 'granted_by', 'expires_at')
            ->withTimestamps();
    }

    public function primaryRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'primary_role_id');
    }

    public function accessProfiles(): BelongsToMany
    {
        return $this->belongsToMany(AccessProfile::class, 'user_access_profiles')
            ->withPivot('granted_at', 'granted_by', 'expires_at')
            ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user')
            ->withPivot('scope', 'granted_at', 'granted_by', 'expires_at', 'is_restricted')
            ->withTimestamps();
    }

    public function temporaryPermissions(): HasMany
    {
        return $this->hasMany(TemporaryPermissionGrant::class);
    }

    public function permissionRestrictions(): HasMany
    {
        return $this->hasMany(PermissionRestriction::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(PermissionAuditLog::class, 'user_id');
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission(string $permissionName, ?string $scope = null): bool
    {
        // Verificar restricciones explícitas primero
        if ($this->hasRestriction($permissionName)) {
            return false;
        }

        // Verificar permisos temporales
        $tempPermission = $this->temporaryPermissions()
            ->where('permission_name', $permissionName)
            ->where('expires_at', '>', now())
            ->where('is_active', true)
            ->first();

        if ($tempPermission) {
            if ($scope && $tempPermission->scope && $tempPermission->scope !== $scope) {
                return false;
            }
            return true;
        }

        // Verificar permisos individuales
        $individualPermission = $this->permissions()
            ->where('name', $permissionName)
            ->wherePivot('is_restricted', false)
            ->where(function ($q) {
                $q->whereNull('permission_user.expires_at')
                  ->orWhere('permission_user.expires_at', '>', now());
            })
            ->first();

        if ($individualPermission) {
            if ($scope && $individualPermission->pivot->scope && $individualPermission->pivot->scope !== $scope) {
                return false;
            }
            return true;
        }

        // Verificar permisos a través de roles
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }

        // Verificar permisos a través de perfiles de acceso
        foreach ($this->accessProfiles as $profile) {
            if ($profile->hasPermission($permissionName)) {
                return true;
            }
        }

        return false;
    }

    public function hasRestriction(string $permissionName): bool
    {
        return $this->permissionRestrictions()
            ->where('permission_name', $permissionName)
            ->where('is_active', true)
            ->exists();
    }

    public function getLevel(): int
    {
        return $this->primaryRole?->level ?? self::LEVEL_EMPLOYEE;
    }

    public function canActOn(User $targetUser): bool
    {
        $targetLevel = $targetUser->getLevel();
        $myLevel = $this->getLevel();

        // No puedo actuar sobre alguien de igual o mayor nivel
        if ($targetLevel >= $myLevel && $this->id !== $targetUser->id) {
            return false;
        }

        return true;
    }

    public function getEffectivePermissions(): array
    {
        $permissions = [];

        // Permisos de roles
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $perm) {
                $permissions[$perm->name] = [
                    'source' => 'role',
                    'source_name' => $role->name,
                    'scope' => 'global',
                ];
            }
        }

        // Permisos de perfiles de acceso
        foreach ($this->accessProfiles as $profile) {
            foreach ($profile->permissions as $perm) {
                $permissions[$perm->name] = [
                    'source' => 'access_profile',
                    'source_name' => $profile->name,
                    'scope' => 'global',
                ];
            }
        }

        // Permisos individuales
        foreach ($this->permissions as $perm) {
            if ($perm->pivot->is_restricted) {
                continue;
            }
            if ($perm->pivot->expires_at && $perm->pivot->expires_at < now()) {
                continue;
            }
            $permissions[$perm->name] = [
                'source' => 'individual',
                'source_name' => 'Directo',
                'scope' => $perm->pivot->scope ?? 'global',
                'expires_at' => $perm->pivot->expires_at,
            ];
        }

        // Permisos temporales
        foreach ($this->temporaryPermissions()->where('expires_at', '>', now())->where('is_active', true)->get() as $temp) {
            $permissions[$temp->permission_name] = [
                'source' => 'temporary',
                'source_name' => 'Temporal',
                'scope' => $temp->scope ?? 'global',
                'expires_at' => $temp->expires_at,
                'reason' => $temp->reason,
            ];
        }

        // Aplicar restricciones
        foreach ($this->permissionRestrictions()->where('is_active', true)->get() as $restriction) {
            unset($permissions[$restriction->permission_name]);
        }

        return $permissions;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('administrador_general');
    }

    public function isManager(): bool
    {
        return $this->hasRole('gerente');
    }

    public function isHR(): bool
    {
        return $this->hasRole('recursos_humanos');
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('empleado') || $this->hasRole('empleado_por_hora');
    }

    public function isAccountSuspended(): bool
    {
        return $this->person?->status === Person::STATUS_ACCOUNT_SUSPENDED;
    }
}

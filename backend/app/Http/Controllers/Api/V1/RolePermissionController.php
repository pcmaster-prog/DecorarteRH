<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AccessProfile;
use App\Models\User;
use App\Models\TemporaryPermissionGrant;
use App\Models\PermissionRestriction;
use App\Models\CriticalPermissionGrant;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function roles()
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->orderBy('level', 'desc')
            ->get();

        return response()->json([
            'data' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'description' => $role->description,
                    'level' => $role->level,
                    'users_count' => $role->users_count,
                    'permissions_count' => $role->permissions_count,
                    'is_active' => $role->is_active,
                    'is_system' => $role->is_system,
                ];
            }),
        ]);
    }

    public function permissions()
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();

        return response()->json([
            'data' => $permissions->groupBy('module')->map(function ($group) {
                return $group->map(function ($perm) {
                    return [
                        'id' => $perm->id,
                        'name' => $perm->name,
                        'display_name' => $perm->display_name,
                        'description' => $perm->description,
                        'action' => $perm->action,
                        'is_critical' => $perm->is_critical,
                        'requires_approval' => $perm->requires_approval,
                    ];
                });
            }),
        ]);
    }

    public function accessProfiles()
    {
        $profiles = AccessProfile::withCount(['users', 'permissions'])->get();

        return response()->json([
            'data' => $profiles->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'display_name' => $profile->display_name,
                    'description' => $profile->description,
                    'users_count' => $profile->users_count,
                    'permissions_count' => $profile->permissions_count,
                    'is_active' => $profile->is_active,
                ];
            }),
        ]);
    }

    public function userPermissions($userId)
    {
        $user = User::with(['roles.permissions', 'accessProfiles.permissions', 'permissions', 'temporaryPermissions', 'permissionRestrictions'])
            ->findOrFail($userId);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->person?->full_name,
                'email' => $user->email,
            ],
            'effective_permissions' => $user->getEffectivePermissions(),
            'roles' => $user->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'is_primary' => $role->pivot->is_primary,
                    'expires_at' => $role->pivot->expires_at,
                ];
            }),
            'access_profiles' => $user->accessProfiles->map(function ($profile) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'display_name' => $profile->display_name,
                ];
            }),
            'temporary_permissions' => $user->temporaryPermissions->where('expires_at', '>', now())->map(function ($temp) {
                return [
                    'permission_name' => $temp->permission_name,
                    'scope' => $temp->scope,
                    'expires_at' => $temp->expires_at,
                    'reason' => $temp->reason,
                ];
            }),
            'restrictions' => $user->permissionRestrictions->where('is_active', true)->map(function ($rest) {
                return [
                    'permission_name' => $rest->permission_name,
                    'reason' => $rest->reason,
                ];
            }),
        ]);
    }

    public function simulateAccess(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($request->user_id);

        $permissions = $user->getEffectivePermissions();
        $modules = [];

        foreach ($permissions as $permName => $permData) {
            $parts = explode('.', $permName);
            $module = $parts[0];
            $action = $parts[1] ?? 'view';

            if (!isset($modules[$module])) {
                $modules[$module] = [
                    'can_view' => false,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_approve' => false,
                    'actions' => [],
                ];
            }

            $modules[$module]['actions'][] = $action;
            $modules[$module]["can_{$action}"] = true;
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->person?->full_name,
                'role' => $user->primaryRole?->display_name,
                'level' => $user->getLevel(),
            ],
            'modules' => $modules,
            'total_permissions' => count($permissions),
            'blocked_modules' => array_diff(
                ['dashboard', 'employees', 'kardex', 'attendance', 'tasks', 'routines', 'reports', 'payroll', 'settings'],
                array_keys($modules)
            ),
        ]);
    }

    public function grantTemporaryPermission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_name' => 'required|string',
            'scope' => 'nullable|string',
            'expires_at' => 'required|date|after:now',
            'reason' => 'required|string|min:10',
        ]);

        $grant = TemporaryPermissionGrant::create([
            'user_id' => $request->user_id,
            'permission_name' => $request->permission_name,
            'scope' => $request->scope ?? 'global',
            'granted_by' => auth()->id(),
            'expires_at' => $request->expires_at,
            'reason' => $request->reason,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Permiso temporal otorgado', 'grant' => $grant], 201);
    }

    public function addRestriction(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_name' => 'required|string',
            'reason' => 'required|string|min:5',
        ]);

        $restriction = PermissionRestriction::create([
            'user_id' => $request->user_id,
            'permission_name' => $request->permission_name,
            'reason' => $request->reason,
            'created_by' => auth()->id(),
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Restricción agregada', 'restriction' => $restriction], 201);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->where('is_active', true)
            ->with(['person', 'primaryRole', 'roles', 'accessProfiles'])
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar si la cuenta está suspendida
        if ($user->isAccountSuspended()) {
            return response()->json([
                'message' => 'Tu cuenta ha sido suspendida preventivamente. Contacta a tu administrador o gerente.',
                'suspended' => true,
            ], 403);
        }

        $token = $user->createToken('auth-token', ['*'])->plainTextToken;

        $user->update(['last_login_at' => now()]);

        return response()->json([
            'token' => $token,
            'user' => $this->formatUser($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load(['person', 'primaryRole', 'roles', 'accessProfiles']);
        return response()->json($this->formatUser($user));
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth-token', ['*'])->plainTextToken;
        return response()->json(['token' => $token]);
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->person?->full_name ?? 'Usuario',
            'avatar' => $user->avatar,
            'role' => $user->primaryRole?->display_name ?? 'Sin rol',
            'role_level' => $user->getLevel(),
            'is_active' => $user->is_active,
            'last_login' => $user->last_login_at,
            'permissions' => $user->getEffectivePermissions(),
            'person' => $user->person ? [
                'id' => $user->person->id,
                'first_name' => $user->person->first_name,
                'last_name' => $user->person->last_name,
                'email' => $user->person->email,
                'phone' => $user->person->phone,
                'photo_url' => $user->person->photo_url,
                'status' => $user->person->status,
                'status_label' => $user->person->status_label,
                'status_color' => $user->person->status_color,
            ] : null,
        ];
    }
}

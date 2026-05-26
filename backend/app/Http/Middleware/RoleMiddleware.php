<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            return response()->json(['message' => 'No tienes el rol requerido para esta acción'], 403);
        }
        return $next($request);
    }
}

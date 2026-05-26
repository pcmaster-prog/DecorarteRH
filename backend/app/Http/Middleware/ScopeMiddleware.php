<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScopeMiddleware
{
    public function handle(Request $request, Closure $next, string $scope)
    {
        // Implementar lógica de alcance según jerarquía
        return $next($request);
    }
}

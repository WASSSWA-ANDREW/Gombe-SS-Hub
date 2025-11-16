<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = auth()->user();

        // If no specific role is required, just check if user is authenticated
        if ($role === null) {
            return $next($request);
        }

        // Check if user has the required role
        // Super admin has access to everything
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Admin role check
        if ($role === 'admin' && in_array($user->role, ['admin', 'super_admin'])) {
            return $next($request);
        }

        // Exact role match
        if ($user->role === $role) {
            return $next($request);
        }

        // User doesn't have required role
        abort(403, 'Unauthorized access. You do not have permission to access this page.');
    }
}
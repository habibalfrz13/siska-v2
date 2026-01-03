<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role-based Access Control Middleware
 * 
 * Restricts access based on user roles
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param string|array $roles - Allowed roles (comma-separated or array)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.',
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Convert role names to IDs if needed
        $allowedRoles = $this->parseRoles($roles);
        
        if (!in_array($user->id_role, $allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Insufficient permissions.',
                ], 403);
            }
            
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    /**
     * Parse role names to IDs
     */
    protected function parseRoles(array $roles): array
    {
        $roleMap = [
            'admin' => 1,
            'user' => 2,
        ];

        return array_map(function ($role) use ($roleMap) {
            // If already numeric, return as is
            if (is_numeric($role)) {
                return (int) $role;
            }
            // Otherwise, map the name to ID
            return $roleMap[strtolower($role)] ?? 0;
        }, $roles);
    }
}

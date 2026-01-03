<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rate Limiter Middleware for Login Attempts
 * 
 * Protects against brute force attacks by limiting login attempts
 */
class ThrottleLoginAttempts
{
    /**
     * Maximum attempts allowed
     */
    protected int $maxAttempts = 5;

    /**
     * Decay time in minutes
     */
    protected int $decayMinutes = 15;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                'retry_after' => $seconds,
            ], 429);
        }

        $response = $next($request);

        // If login failed, increment the counter
        if ($response->getStatusCode() === 401 || 
            ($request->session()->has('errors') && $request->session()->get('errors')->has('email'))) {
            RateLimiter::hit($key, $this->decayMinutes * 60);
        } else {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);
        }

        return $response;
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}

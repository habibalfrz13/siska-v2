<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Headers Middleware
 * 
 * Adds essential security headers to protect against common web vulnerabilities:
 * - XSS attacks
 * - Clickjacking
 * - MIME type sniffing
 * - Information disclosure
 */
class SecureHeaders
{
    /**
     * Security headers to apply
     */
    private array $securityHeaders = [
        // Prevent clickjacking attacks
        'X-Frame-Options' => 'SAMEORIGIN',
        
        // Enable XSS filter in browsers
        'X-XSS-Protection' => '1; mode=block',
        
        // Prevent MIME type sniffing
        'X-Content-Type-Options' => 'nosniff',
        
        // Control referrer information
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        
        // Permissions policy (formerly Feature-Policy)
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(self)',
        
        // Remove server version info
        'X-Powered-By' => '',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        foreach ($this->securityHeaders as $header => $value) {
            if ($value === '') {
                $response->headers->remove($header);
            } else {
                $response->headers->set($header, $value);
            }
        }

        // Add Content Security Policy for production
        if (app()->environment('production')) {
            $response->headers->set(
                'Content-Security-Policy',
                $this->getContentSecurityPolicy()
            );
        }

        return $response;
    }

    /**
     * Get Content Security Policy directives
     */
    private function getContentSecurityPolicy(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.datatables.net https://app.sandbox.midtrans.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://api.openweathermap.org https://api.sandbox.midtrans.com",
            "frame-src 'self' https://app.sandbox.midtrans.com",
        ]);
    }
}

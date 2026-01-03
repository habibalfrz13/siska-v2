<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sanitize Input Middleware
 * 
 * Sanitizes user input to prevent XSS and injection attacks
 */
class SanitizeInput
{
    /**
     * Fields to exclude from sanitization (e.g., password, HTML editors)
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        'deskripsi', // Allow HTML for rich text editor
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        
        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value) && !in_array($key, $this->except)) {
                // Remove null bytes
                $value = str_replace(chr(0), '', $value);
                
                // Trim whitespace
                $value = trim($value);
                
                // Strip tags except for specific fields
                if (!$this->shouldAllowHtml($key)) {
                    $value = strip_tags($value);
                }
                
                // Encode special characters
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
            }
        });

        $request->merge($input);

        return $next($request);
    }

    /**
     * Check if field should allow HTML
     */
    protected function shouldAllowHtml(string $key): bool
    {
        $htmlFields = ['deskripsi', 'content', 'body', 'description'];
        return in_array($key, $htmlFields);
    }
}

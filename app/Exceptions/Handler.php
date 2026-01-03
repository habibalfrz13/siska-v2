<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle API requests
        if ($request->is('api/*') || $request->wantsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle exceptions for API requests
     */
    protected function handleApiException($request, Throwable $e)
    {
        // Model not found
        if ($e instanceof ModelNotFoundException) {
            $modelName = class_basename($e->getModel());
            return response()->json([
                'success' => false,
                'message' => "Data {$modelName} tidak ditemukan.",
                'error' => [
                    'type' => 'not_found',
                    'model' => $modelName,
                ],
            ], 404);
        }

        // Route not found
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint tidak ditemukan.',
                'error' => [
                    'type' => 'not_found',
                ],
            ], 404);
        }

        // Method not allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Metode HTTP tidak diizinkan.',
                'error' => [
                    'type' => 'method_not_allowed',
                ],
            ], 405);
        }

        // Validation error
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Authentication error
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum login atau sesi telah berakhir.',
                'error' => [
                    'type' => 'unauthenticated',
                ],
            ], 401);
        }

        // Server error
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        
        return response()->json([
            'success' => false,
            'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server.',
            'error' => config('app.debug') ? [
                'type' => class_basename($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->take(5)->toArray(),
            ] : [
                'type' => 'server_error',
            ],
        ], $statusCode);
    }
}

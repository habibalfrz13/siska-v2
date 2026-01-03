<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * API Response Trait
 * 
 * Provides consistent JSON response methods for API controllers
 * Following RESTful API best practices
 */
trait ApiResponse
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Created response (201)
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    protected function created(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return $this->success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * No content response (204)
     *
     * @return JsonResponse
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $statusCode
     * @param array|null $errors
     * @return JsonResponse
     */
    protected function error(
        string $message = 'Error',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        ?array $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error response (422)
     *
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    protected function validationError(
        array $errors,
        string $message = 'Validasi gagal'
    ): JsonResponse {
        return $this->error($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Unauthorized response (401)
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function unauthorized(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return $this->error($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Forbidden response (403)
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function forbidden(
        string $message = 'Forbidden'
    ): JsonResponse {
        return $this->error($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Not found response (404)
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function notFound(
        string $message = 'Resource not found'
    ): JsonResponse {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Paginated response
     *
     * @param mixed $paginator
     * @param string $message
     * @return JsonResponse
     */
    protected function paginated(
        mixed $paginator,
        string $message = 'Data retrieved successfully'
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }
}

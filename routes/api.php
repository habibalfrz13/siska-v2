<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| SISKA RESTful API v1
| 
| Following REST conventions:
| - Use nouns for resources (not verbs)
| - Use plural names for collections
| - Use HTTP methods for actions (GET, POST, PUT, PATCH, DELETE)
| - Use proper HTTP status codes
| - Version the API
|
*/

// API Version 1
Route::prefix('v1')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        // Public
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
        
        // Protected
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/logout-all', [AuthController::class, 'logoutAll']);
            Route::put('/password', [AuthController::class, 'changePassword']);
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Kelas (Classes) Resource Routes
    |--------------------------------------------------------------------------
    */
    
    // Public - Read only
    Route::get('/kelas', [KelasController::class, 'index']);
    Route::get('/kelas/{kelas}', [KelasController::class, 'show']);
    
    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/kelas/available', [KelasController::class, 'available']);
        Route::post('/kelas/{kelas}/enrollments', [KelasController::class, 'enroll']);
        Route::get('/users/me/classes', [KelasController::class, 'myClasses']);
        
        // Admin only - Full CRUD
        Route::middleware('role:admin')->group(function () {
            Route::post('/kelas', [KelasController::class, 'store']);
            Route::put('/kelas/{kelas}', [KelasController::class, 'update']);
            Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy']);
        });
    });
    
    /*
    |--------------------------------------------------------------------------
    | Transaksi (Transactions) Resource Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {
        // User transactions
        Route::get('/transaksi', [TransaksiController::class, 'index']);
        Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show']);
        Route::patch('/transaksi/{transaksi}/cancel', [TransaksiController::class, 'cancel']);
        
        // Admin only
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/transaksi', [TransaksiController::class, 'all']);
            Route::get('/admin/statistics', [TransaksiController::class, 'statistics']);
            Route::patch('/transaksi/{transaksi}/confirm', [TransaksiController::class, 'confirmPayment']);
        });
    });
});

/*
|--------------------------------------------------------------------------
| API Health Check & Meta
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'service' => 'SISKA API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
        'environment' => app()->environment(),
    ]);
});

Route::get('/', function () {
    return response()->json([
        'name' => 'SISKA API',
        'version' => '1.0.0',
        'documentation' => url('/api/docs'),
        'endpoints' => [
            'health' => url('/api/health'),
            'v1' => url('/api/v1'),
        ],
    ]);
});


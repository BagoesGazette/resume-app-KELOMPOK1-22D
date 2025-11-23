<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CvSubmissionController;
use App\Http\Controllers\Api\TokenController;

// Test endpoint
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working',
        'timestamp' => now(),
        'laravel_version' => app()->version(),
    ]);
});

// Public routes
Route::post('/token/create', [TokenController::class, 'create']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Token management
    Route::prefix('tokens')->group(function () {
        Route::get('/', [TokenController::class, 'index']);
        Route::delete('/{id}', [TokenController::class, 'destroy']);
        Route::delete('/', [TokenController::class, 'destroyAll']);
    });
    
    // CV Submissions
    Route::prefix('cv-submissions')->group(function () {
        Route::get('/', [CvSubmissionController::class, 'index']);
        Route::post('/', [CvSubmissionController::class, 'store']);
        Route::get('/{id}', [CvSubmissionController::class, 'show']);
        Route::put('/{id}', [CvSubmissionController::class, 'update']);
        Route::delete('/{id}', [CvSubmissionController::class, 'destroy']);
    });
});
<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\CvSubmissionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function(){

    Route::get('/', DashboardController::class)->name('home');

    // Route untuk Kandidat
    Route::get('/lowongan-kerja', function () {
        return view('kandidat.lowongan-kerja.index');
    })->name('lowongan-kerja.index');

    Route::get('/hasil', function () {
        return view('kandidat.hasil.index');
    })->name('hasil.index');

});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // CV Submission routes
    Route::prefix('cv-submissions')->group(function () {
        Route::get('/', [CvSubmissionController::class, 'index']); // List all submissions
        Route::post('/', [CvSubmissionController::class, 'store']); // Upload new CV
        Route::get('/{id}', [CvSubmissionController::class, 'show']); // Get specific submission
        Route::put('/{id}', [CvSubmissionController::class, 'update']); // Update/validate submission
        Route::delete('/{id}', [CvSubmissionController::class, 'destroy']); // Delete submission
    });
});

// Test route (remove in production)
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working',
        'timestamp' => now(),
    ]);
});
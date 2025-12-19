<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Kandidat\JobApplicationController;
use App\Http\Controllers\Kandidat\LowonganKerjaController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProses'])->name('register');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProses'])->name('login');
Route::get('/confirm-email/{id}', [AuthController::class, 'confirmEmail'])->name('confirm-email');


Route::group(['middleware' => ['auth']], function(){

    Route::get('/', DashboardController::class)->name('home');

    // Route untuk Kandidat
    Route::get('/lowongan-kerja', [LowonganKerjaController::class, 'index'])->name('lowongan-kerja.index');
    Route::get('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'show'])->name('lowongan-kerja.show');
    Route::get('/lowongan-kerja/{id}/create', [LowonganKerjaController::class, 'create'])->name('lowongan-kerja.form');
    Route::get('/lowongan-kerja/{id}/detail', [LowonganKerjaController::class, 'detail'])->name('lowongan-kerja.detail');
    Route::post('/lowongan-kerja/cv', [LowonganKerjaController::class, 'store'])->name('cv.store');
    Route::get('/cv-submission/{id}/status', [LowonganKerjaController::class, 'status'])->name('cv.status');
    Route::post('/lowongan-kerja/{id}/process-cv', [JobApplicationController::class, 'processCv'])->name('lowongan-kerja.process-cv');
    Route::get('/lowongan-kerja/{id}/detail', [LowonganKerjaController::class, 'detail'])->name('lowongan-kerja.detail');

    Route::get('/lamaran/{id}/validate', [JobApplicationController::class, 'validate'])->name('lamaran.validate');
    Route::post('/lamaran/{id}/submit', [JobApplicationController::class, 'submit'])->name('lamaran.submit');

    Route::get('/pelamar/{id}', [PelamarController::class, 'show'])->name('pelamar.show');

    Route::get('/hasil', function () {
        return view('kandidat.hasil.index');
    })->name('hasil.index');

    Route::get('/kriteria', KriteriaController::class)->name('kriteria.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/job/{id}/close', [JobController::class, 'close'])->name('job.close');


    Route::resource('job', JobController::class);
    Route::resource('users', UserController::class);
});
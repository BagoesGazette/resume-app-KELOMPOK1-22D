<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Kandidat\HasilController;
use App\Http\Controllers\Kandidat\JobApplicationController;
use App\Http\Controllers\Kandidat\LowonganKerjaController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProses'])->name('register.custom');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProses'])->name('login.custom');
Route::get('/confirm-email/{id}', [AuthController::class, 'confirmEmail'])->name('confirm-email');


Route::group(['middleware' => ['auth']], function(){

    Route::get('/', DashboardController::class)->name('home');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

    // Route untuk Kandidat
    Route::get('/lowongan-kerja', [LowonganKerjaController::class, 'index'])->name('lowongan-kerja.index');
    Route::get('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'show'])->name('lowongan-kerja.show');
    Route::get('/lowongan-kerja/{id}/create', [LowonganKerjaController::class, 'create'])->name('lowongan-kerja.form');
    Route::get('/lowongan-kerja/{id}/detail', [LowonganKerjaController::class, 'detail'])->name('lowongan-kerja.detail');
    Route::post('/lowongan-kerja/cv', [LowonganKerjaController::class, 'store'])->name('cv.store');
    Route::get('/cv-submission/{id}/status', [LowonganKerjaController::class, 'status'])->name('cv.status');
    Route::get('/cv/edit', [LowonganKerjaController::class, 'editCv'])->name('cv.edit');
    Route::post('/lowongan-kerja/{id}/process-cv', [JobApplicationController::class, 'processCv'])->name('lowongan-kerja.process-cv');
    //Route::get('/lowongan-kerja/{id}/detail', [LowonganKerjaController::class, 'detail'])->name('lowongan-kerja.detail');

    Route::get('/lamaran/{id}/validate', [JobApplicationController::class, 'validate'])->name('lamaran.validate');
    Route::post('/lamaran/{id}/submit', [JobApplicationController::class, 'submit'])->name('lamaran.submit');

    Route::get('/pelamar/{id}', [PelamarController::class, 'show'])->name('pelamar.show');

    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');

    Route::get('/kriteria', KriteriaController::class)->name('kriteria.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/job/{id}/close', [JobController::class, 'close'])->name('job.close');
    Route::get('/job/{id}/scoring', [JobController::class, 'scoring'])->name('job.scoring');


    Route::resource('job', JobController::class);
    Route::resource('users', UserController::class);

    Route::put('/pelamar/{id}/interview', [JobApplicationController::class, 'scheduleInterview'])->name('pelamar.interview');
    Route::put('/pelamar/{id}/accept', [JobApplicationController::class, 'acceptApplicant'])->name('pelamar.accept');
    Route::put('/pelamar/{id}/reject', [JobApplicationController::class, 'rejectApplicant'])->name('pelamar.reject');

    // routes/web.php
    Route::get('/job/{id}/export/excel', [JobController::class, 'exportExcel'])->name('job.export.excel');
    Route::get('/job/{id}/export/pdf', [JobController::class, 'exportPdf'])->name('job.export.pdf');

    Route::delete('/hasil/{id}/cancel', [HasilController::class, 'cancel'])->name('kandidat.hasil.cancel');
    Route::get('/hasil/export/excel', [HasilController::class, 'exportExcel'])->name('kandidat.hasil.export.excel');
    Route::get('/hasil/export/pdf', [HasilController::class, 'exportPdf'])->name('kandidat.hasil.export.pdf');
});
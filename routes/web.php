<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KriteriaController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProses'])->name('register');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProses'])->name('login');
Route::get('/confirm-email/{id}', [AuthController::class, 'confirmEmail'])->name('confirm-email');


Route::group(['middleware' => ['auth']], function(){

    Route::get('/', DashboardController::class)->name('home');

    // Route untuk Kandidat
    Route::get('/lowongan-kerja', function () {
        return view('kandidat.lowongan-kerja.index');
    })->name('lowongan-kerja.index');

    Route::get('/hasil', function () {
        return view('kandidat.hasil.index');
    })->name('hasil.index');

    Route::get('/kriteria', KriteriaController::class)->name('kriteria.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
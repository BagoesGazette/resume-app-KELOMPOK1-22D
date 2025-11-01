<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
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
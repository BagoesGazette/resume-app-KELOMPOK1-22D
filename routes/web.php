<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function(){

    Route::get('/', DashboardController::class)->name('home');

});
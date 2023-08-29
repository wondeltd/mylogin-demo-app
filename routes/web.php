<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginPage')->name('login');
    Route::get('/redirect', 'redirect')->name('redirect');
    Route::get('/callback', 'callback')->name('callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

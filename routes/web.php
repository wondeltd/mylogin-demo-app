<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return to_route('dashboard');
    }

    return to_route('login');
});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginPage')->name('login');
    Route::get('/{auth_target}/redirect', 'redirect')->name('redirect');
    Route::get('/{auth_target}/callback', 'callback')->name('callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

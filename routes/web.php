<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return to_route('dashboard');
    }

    if ($firstAuthTarget = \App\Models\AuthTarget::first()) {
        return to_route('login', ['auth_target' => $firstAuthTarget->slug]);
    }

    abort(503, 'No authentication targets configured');
});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/{auth_target}/login', 'loginPage')->name('login');
    Route::get('/{auth_target}/redirect', 'redirect')->name('redirect');
    Route::get('/{auth_target}/callback', 'callback')->name('callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/{auth_target}/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth:admin')->group(function () {

    Route::get('dashboard', DashboardController::class)
        ->name('dashboard');

    Route::get('accounts', function() {
        abort(404);
    })->name('accounts');

    Route::get('employees', function() {
        abort(404);
    })->name('employees');

    Route::get('calendar', function() {
        abort(404);
    })->name('calendar');

    Route::get('job-listing', function() {
        abort(404);
    })->name('job-listing');

    Route::get('policy', function() {
        abort(404);
    })->name('policy');

    Route::get('announcement', function() {
        abort(404);
    })->name('announcement');

    Route::get('performance', function() {
        abort(404);
    })->name('performance');

    Route::get('form', function() {
        abort(404);
    })->name('form');
});

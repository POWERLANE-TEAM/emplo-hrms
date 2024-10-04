<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Auth\Employees\Login;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth:admin', 'verified')->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');
    Route::get('/sample',  function () {
        dd(request());
        echo 'sample';
    });
});

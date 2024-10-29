<?php

use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\HRManager\ApplicantController as HRApplicantController;
use App\Livewire\Auth\Employees\Login;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;


Route::middleware('guest:employee')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth:employee'/* , 'verified' */)->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    Route::get('/applicants', [HRApplicantController::class, 'index'])
        ->name('applicants');

    Route::get('/sample', function () {
        dd(request());
        echo 'sample';
    });

    Route::post('/logout', [Logout::class, 'destroy'])
        ->name('logout');
});

<?php

use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\HRManager\ApplicantController as HRApplicantController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth'/* , 'verified' */)->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    Route::get('/applicants', [HRApplicantController::class, 'index'])
        ->name('applicants');

    Route::get('/sample', function () {
        dd(request());
        echo 'sample';
    });
});

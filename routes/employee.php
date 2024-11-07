<?php

use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\HRManager\ApplicantController as HRApplicantController;
use App\Livewire\Auth\Employees\Login;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;


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

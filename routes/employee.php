<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\EmployeeController;
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

    Route::get('profile', function () {
            return view('employee.profile.settings');
    })->name('profile');

    Route::get('probationary-perf-results', function () {
        return view('employee.hr-manager.performance.probationary.performance-results');
    })->name('probationary-perf-results');

    Route::get('/index', [EmployeeController::class, 'index'])
        ->name('index');


    Route::get('/attendance/index', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::get('/sample', function () {
        dd(request());
        echo 'sample';
    });
});

Route::get('profile', function () {
    return view('employee.admin.profile');
})->name('profile'); // Still temporary.

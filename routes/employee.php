<?php

use App\Enums\UserPermission;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Controllers\InitialInterviewController;
use App\Livewire\Auth\Employees\Login;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth'/* , 'verified' */)->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->can(UserPermission::VIEW_HR_MANAGER_DASHBOARD)
        ->name('dashboard');

    Route::get('/applicants/{page?}', [ApplicationController::class, 'index'])
        ->where('page', 'index|')
        ->can(UserPermission::VIEW_ALL_APPLICANTS)
        ->name('applications');

    Route::get('/applicant/{application}', [ApplicationController::class, 'show'])
        ->middleware(['permission:' . UserPermission::VIEW_APPLICANT_INFORMATION->value . '|' . UserPermission::VIEW_ALL_APPLICANTS->value])
        ->name('application.show');

    Route::patch('/applicant/{application}', [ApplicationController::class, 'update'])
        ->middleware(['permission:' . UserPermission::UPDATE_APPLICATION_STATUS->value])
        ->name('application.update');

    Route::post('/applicant/interview/initial/{application}', [InitialInterviewController::class, 'store'])
        ->middleware(['permission:' . UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE->value])
        ->name('applicant.initial-inteview.store');

    Route::post('/applicant/exam/{application}', [ApplicationExamController::class, 'store'])
        ->middleware(['permission:' . UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE->value])
        ->name('applicant.exam.store');

    Route::get('/sample', function () {
        dd(request());
        echo 'sample';
    });
});

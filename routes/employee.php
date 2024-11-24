<?php

use App\Enums\UserPermission;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Controllers\InitialInterviewController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


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

    Route::get('profile', function () {
        return view('employee.profile.settings');
    })->name('profile');

    // Performance Evaluation Results
    Route::get('evaluation-results/probationary', function () {
        return view('/employee.hr-manager.evaluations.probationary.evaluation-results');
    })->name('evaluation-results.probationary');

    Route::get('evaluation-results/regular', function () {
        return view('/employee.hr-manager.evaluations.regular.evaluation-results');
    })->name('evaluation-results.regular');


    // Performance Evaluation Scoring
    Route::get('/assign-score/probationary', function () {
        return view('employee.supervisor.evaluations.probationary.assign-score');
    })->name('assign-score.probationary');

    Route::get('/assign-score/regular', function () {
        return view('employee.supervisor.evaluations.regular.assign-score');
    })->name('assign-score.regular');


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

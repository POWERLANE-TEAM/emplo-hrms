<?php

use App\Enums\UserPermission;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InitialInterviewController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth'/* , 'verified' */)->group(function () {

    // =========================================
    // HR MANAGER ROUTES
    // ==========================================

    // Dashboard
    // ----------
    Route::get('/dashboard', DashboardController::class)
        ->middleware(['permission:'.UserPermission::VIEW_HR_MANAGER_DASHBOARD->value
            .'|'.UserPermission::VIEW_EMPLOYEE_DASHBOARD->value])
        ->name('dashboard');

    // List Applications Based on Status
    // ----------------------------------
    // Handles the listing of applications filtered by status (pending, qualified, or pre-employed)
    Route::get('/applicants/{applicationStatus}/{page?}', [ApplicationController::class, 'index'])
        ->where('applicationStatus', 'pending|qualified|preemployed')
        ->where('page', 'index|')
        ->middleware([
            'permission:'.UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value
            .'|'.UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value
            .'|'.UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value,
        ])
        ->name('applications');

    // View Specific Application Details
    // ----------------------------------
    // Displays detailed information for a specific application
    Route::get('/applicant/{application}', [ApplicationController::class, 'show'])
        ->middleware([
            'permission:'.UserPermission::VIEW_APPLICATION_INFORMATION->value.'&('.UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value
            .'|'.UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value
            .'|'.UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value.')',
        ])
        ->name('application.show');

    // Update Application Status
    // --------------------------
    // Updates the status of an application (pending, qualified, or pre-employed)
    Route::patch('/applicant/{application}', [ApplicationController::class, 'update'])
        ->middleware([
            'permission:'.UserPermission::UPDATE_PENDING_APPLICATION_STATUS->value
            .'|'.UserPermission::UPDATE_QUALIFIED_APPLICATION_STATUS->value
            .'|'.UserPermission::UPDATE_PRE_EMPLOYED_APPLICATION_STATUS->value,
        ])
        ->name('application.update');

    // Schedule Initial Interview
    // ---------------------------
    // Creates a schedule for the applicant's initial interview
    Route::post('/applicant/interview/initial/{application}', [InitialInterviewController::class, 'store'])
        ->middleware(['permission:'.UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE->value])
        ->name('applicant.initial-inteview.store');

    // Schedule Exam for Applicant
    // ----------------------------
    // Creates a schedule for the applicant's exam
    Route::post('/applicant/exam/{application}', [ApplicationExamController::class, 'store'])
        ->middleware(['permission:'.UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE->value])
        ->name('applicant.exam.store');

    // Employee Profile Settings
    // --------------------------
    Route::get('profile', function () {
        return view('employee.profile.settings');
    })->name('profile');

    // Performance Evaluation Results
    // -------------------------------
    Route::get('evaluation-results/probationary', function () {
        return view('/employee.hr-manager.evaluations.probationary.evaluation-results');
    })->name('evaluation-results.probationary');

    Route::get('evaluation-results/regular', function () {
        return view('/employee.hr-manager.evaluations.regular.evaluation-results');
    })->name('evaluation-results.regular');

    // =========================================
    // SUPERVISOR ROUTES
    // ==========================================

    // Performance Evaluation Scoring
    // -------------------------------
    Route::get('/assign-score/probationary', function () {
        return view('employee.supervisor.evaluations.probationary.assign-score');
    })->name('assign-score.probationary');

    Route::get('/assign-score/regular', function () {
        return view('employee.supervisor.evaluations.regular.assign-score');
    })->name('assign-score.regular');

    // =========================================
    // BASIC EMPLOYEE ROUTES
    // ==========================================
    Route::get('/index', [EmployeeController::class, 'index'])
        ->name('index');

    Route::get('/attendance/index', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::get('/sample', function () {
        dd(request());
        echo 'sample';
    });
});

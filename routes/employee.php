<?php

use App\Models\Employee;
use App\Enums\UserPermission;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Controllers\InitialInterviewController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\PerformanceDetailController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth'/* , 'verified' */)->group(function () {

    // =========================================
    // HR MANAGER ROUTES
    // ==========================================

    /**
     * Dashboard
     */
    Route::get('/dashboard', DashboardController::class)
        ->middleware([
            'permission:' . UserPermission::VIEW_HR_MANAGER_DASHBOARD->value
                . '|' . UserPermission::VIEW_EMPLOYEE_DASHBOARD->value
        ])
        ->name('dashboard');


    /**
     * List of Applicants
     */
    Route::get('/applicants/{applicationStatus}/{page?}', [ApplicationController::class, 'index'])
        ->where('applicationStatus', 'pending|qualified|preemployed')
        ->where('page', 'index|')
        ->middleware([
            'permission:' . UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value
                . '|' . UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value
                . '|' . UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value,
        ])
        ->name('applications');


    /**
     * View Specific Application Details
     */
    Route::get('/applicant/{application}', [ApplicationController::class, 'show'])
        ->middleware([
            'permission:' . UserPermission::VIEW_APPLICATION_INFORMATION->value . '&(' . UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value
                . '|' . UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value
                . '|' . UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value . ')',
        ])
        ->name('application.show');


    /**
     * Update Application Status
     */
    Route::patch('/applicant/{application}', [ApplicationController::class, 'update'])
        ->middleware([
            'permission:' . UserPermission::UPDATE_PENDING_APPLICATION_STATUS->value
                . '|' . UserPermission::UPDATE_QUALIFIED_APPLICATION_STATUS->value
                . '|' . UserPermission::UPDATE_PRE_EMPLOYED_APPLICATION_STATUS->value,
        ])
        ->name('application.update');


    /**
     * Schedule Initial Interview
     */
    Route::post('/applicant/interview/initial/{application}', [InitialInterviewController::class, 'store'])
        ->middleware(['permission:' . UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE->value])
        ->middleware(['permission:' . UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE->value])
        ->name('applicant.initial-inteview.store');


    /**
     * Schedule Exam for Applicant
     */
    Route::post('/applicant/exam/{application}', [ApplicationExamController::class, 'store'])
        ->middleware(['permission:' . UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE->value])
        ->middleware(['permission:' . UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE->value])
        ->name('applicant.exam.store');


    /**
     * Performances
     */
    Route::prefix('performance')->name('performance.')->group(function () {
        Route::prefix('evaluation')->name('evaluation.')->group(function () {
            Route::get('/{employeeStatus}', [PerformanceDetailController::class, 'index'])
                ->where('employeeStatus', 'probationary|regular')
                ->name('index');
        });
    });

    /**
     * Profile
     */
    Route::get('profile', function () {
        return view('employee.profile.settings');
    })->name('profile');


    /**
     * Evaluator 
     */
    Route::get('resume-evaluator/rankings', function () {
        return view('/employee.hr-manager.resume-evaluator.rankings');
    })->name('resume-evaluator.rankings');


    /**
     * Performance Evaluation Results
     */
    Route::get('evaluation-results/probationary', function () {
        return view('/employee.hr-manager.evaluations.probationary.evaluation-results');
    })->name('evaluation-results.probationary');

    Route::get('evaluation-results/regular', function () {
        return view('/employee.hr-manager.evaluations.regular.evaluation-results');
    })->name('evaluation-results.regular');


    /**
     * Relations: Incidents Management
     */
    Route::get('/incidents/create', function () {
        return view('employee.hr-manager.incidents.create');
    })->name('relations.incidents.create');


    /**
     * Relations: Issues
     */
    Route::get('/issues/review', function () {
        return view('employee.hr-manager.issues.review');
    })->name('relations.issues.review');


    /**
     * Training
     */
    Route::get('/training/all-records', function () {
        return view('employee.hr-manager.training.all-records');
    })->name('training.all-records');

    Route::get('/training/records', function () {
        return view('employee.hr-manager.training.records');
    })->name('training.records');


    /**
     * Employees Information
     */

    Route::get('{employee}', function (Employee $employee) {
        return view('employee.hr-manager.employees.information', compact('employee'));
    })->name('employees.information');


    /**
     * Payslips
     */
    Route::get('/payslips/bulk-upload', function () {
        return view('employee.hr-manager.payslips.bulk-upload');
    })->name('payslips.bulk-upload');




    // =========================================
    // SUPERVISOR ROUTES
    // ==========================================

    /**
     * Assign Score
     */
    Route::get('{employee}/performances/create', function (Employee $employee) {
        $employee->with(['performances, jobTitle', 'status']);
        return view('employee.supervisor.performance-evaluations.create', compact('employee'));
    })->name('performances.create');


    Route::get('/assign-score/regular', function () {
        return view('employee.supervisor.evaluations.regular.assign-score');
    })->name('assign-score.regular');



    // =========================================
    // BASIC EMPLOYEE ROUTES
    // ==========================================


    /**
     * Index
     */
    Route::get('/index', [EmployeeController::class, 'index'])
        ->name('index');

    /**
     * Attendance
     */
    Route::get('/attendance/index', [AttendanceController::class, 'index'])
        ->name('attendance.index');


    /**
     * Leaves: Request
     */
    Route::get('/leaves/request', function () {
        return view('employee.basic.leaves.request');
    })->name('leaves.request');


    /**
     * Leaves: View all Leaves
     */
    Route::get('/leaves/view', function () {
        return view('employee.basic.leaves.view');
    })->name('leaves.view');


    /**
     * Overtime: Table of Summary Forms
     */
    Route::get('/overtime/all-summary-forms', function () {
        return view('employee.basic.overtime.all-summary-forms');
    })->name('overtime.all-summary-forms');


    /**
     * Overtime: Summary form per payroll period
     */
    Route::get('/overtime/summary-form', function () {
        return view('employee.basic.overtime.summary-form');
    })->name('overtime.summary-form');


    /**
     * Overtime: Request
     */
    Route::get('/overtime/requests', function () {
        return view('employee.basic.overtime.requests');
    })->name('overtime.requests');


    /**
     * Issues: Create
     */
    Route::get('/issues/create', function () {
        return view('employee.basic.issues.create');
    })->name('issues.create');
});

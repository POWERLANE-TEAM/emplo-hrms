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
     * Performance Evaluation Results: Probationay
     */

    Route::get('hr/evaluation-results/probationary/all', function () {
        return view('/employee.hr-manager.evaluations.probationary.all');
    })->name('hr.evaluation-results.probationary.all');

    Route::get('evaluation-results/probationary', function () {
        return view('/employee.hr-manager.evaluations.probationary.evaluation-results');
    })->name('evaluation-results.probationary');

    /**
     * Performance Evaluation Results: Regular
     */

    Route::get('hr/evaluation-results/regular/all', function () {
        return view('/employee.hr-manager.evaluations.regular.all');
    })->name('hr.evaluation-results.regular.all');
    
    Route::get('evaluation-results/regular', function () {
        return view('/employee.hr-manager.evaluations.regular.evaluation-results');
    })->name('evaluation-results.regular');


    /**
     * HR: Leaves
     */

     Route::get('hr/leaves/all', function () {
        return view('employee.hr-manager.leaves.all');
    })->name('hr.leaves.all');


    /**
     * Overtime
     */

     Route::get('hr/overtime/all', function () {
        return view('employee.hr-manager.overtime.all');
    })->name('hr.overtime.all');


    /**
     * Relations: Incidents Management
     */

    Route::get('hr/relations/incidents/all', function () {
        return view('employee.hr-manager.relations.incidents.all');
    })->name('hr.relations.incidents.all');

    Route::get('hr/relations/incidents/create', function () {
        return view('employee.hr-manager.relations.incidents.create');
    })->name('hr.relations.incidents.create');


    /**
     * Relations: Issues
     */
    Route::get('hr/relations/issues/all', function () {
        return view('employee.hr-manager.relations.issues.all');
    })->name('hr.relations.issues.all');

    Route::get('relations/issues/review', function () {
        return view('employee.hr-manager.relations.issues.review');
    })->name('relations.issues.review');


    /**
     * Training
     */
    Route::get('/training/all', function () {
        return view('employee.hr-manager.training.all');
    })->name('training.all');

    Route::get('/training/records', function () {
        return view('employee.hr-manager.training.records');
    })->name('training.records');


    /**
     * Employees
     */

    Route::get('/employees/all', function () {
        return view('employee.hr-manager.employees.all');
    })->name('employees.all');

    Route::get('{employee}', function (Employee $employee) {
        return view('employee.hr-manager.employees.information', compact('employee'));
    })
        ->whereNumber('employee')
        ->name('employees.information');


    /**
     * Payslips
     */

    Route::get('hr/payslips/all', function () {
        return view('employee.hr-manager.payslips.all');
    })->name('hr.payslips.all');

    Route::get('/payslips/bulk-upload', function () {
        return view('employee.hr-manager.payslips.bulk-upload');
    })->name('payslips.bulk-upload');




    // =========================================
    // SUPERVISOR ROUTES
    // ==========================================

    
    /**
     * Leaves
     */

    Route::get('managerial/requests/leaves/all', function () {
        return view('employee.supervisor.requests.leaves.all');
    })->name('managerial.requests.leaves.all');


    /**
     * Overtime
     */

    Route::get('managerial/requests/overtime/all', function () {
        return view('employee.supervisor.requests.overtime.all');
    })->name('managerial.requests.overtime.all');


    /**
     * Performance Evaluations
     */

     Route::get('managerial/evaluations/all', function () {
        return view('employee.supervisor.performance-evaluations.all');
    })->name('managerial.evaluations.all');

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
    // GENERAL EMPLOYEE ROUTES
    // ==========================================


    /**
     * General: Dashboard
     */
    Route::get('/index', [EmployeeController::class, 'index'])
        ->name('index');


    /**
     * General: Attendance
     */
    Route::get('/attendance/index', [AttendanceController::class, 'index'])
        ->name('attendance.index');


    /**
     * General: Payslip
     */

     Route::get('general/payslips/all', function () {
        return view('employee.basic.payslips.all');
    })->name('general.payslips.all');


    /**
     * General: Leaves
     */

    Route::get('general/leaves/all', function () {
        return view('employee.basic.leaves.all');
    })->name('general.leaves.all');

    Route::get('general/leaves/request', function () {
        return view('employee.basic.leaves.request');
    })->name('general.leaves.request');


    Route::get('general/leaves/view', function () {
        return view('employee.basic.leaves.view');
    })->name('general.leaves.view');


    /**
     * General: Overtime
     */
    Route::get('/overtimes', function () {
        return view('employee.basic.overtime.all');
    })->name('overtimes');

    Route::get('/overtimes/recents', function () {
        return view('employee.basic.overtime.recent-records');
    })->name('overtimes.recents');

    Route::get('/overtimes/archive', function () {
        return view('employee.basic.overtime.index');
    })->name('overtimes.archive');


    /**
     * General: Documents
     */
    Route::get('general/documents/all', function () {
        return view('employee.basic.documents.all');
    })->name('general.documents.all');


    /**
     * General: Issues
     */
    Route::get('general/issues/all', function () {
        return view('employee.basic.issues.all');
    })->name('general.issues.all');

    Route::get('general/issues/create', function () {
        return view('employee.basic.issues.create');
    })->name('general.issues.create');
});

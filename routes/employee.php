<?php

use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingController;
use App\Models\Employee;
use App\Enums\UserPermission;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Controllers\InitialInterviewController;
use App\Http\Controllers\PerformanceDetailController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\RegularPerformanceController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\ProbationaryPerformanceController;
use App\Http\Controllers\RegularPerformancePlanController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth'/* , 'verified' */)->group(function () {

    /**
     * Profile Resource
     */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])
            ->name('index');

        Route::get('edit', [ProfileController::class, 'edit'])
            ->name('edit');

        Route::get('settings', [ProfileController::class, 'settings'])
            ->name('settings');

        Route::get('activity-logs', [ProfileController::class, 'logs'])
            ->name('logs');
    });


    /**
     * Recycle Bin
     */
    Route::get('recycle-bin', function () {
        return view('employee.recycle-bin.index');
    })->name('recycle-bin');


    /**
     * Notifications
     */
    Route::get('notifications', function () {
        return view('employee.notifications.index');
    })->name('notifications');


    /**
     * Organization
     */
    Route::prefix('job-family')->name('job-family.')->group(function () {
        Route::get('/', function () {
            return view('employee.admin.job-family.index');
        })
            ->name('index');

        Route::get('create', function () {
            return view('employee.admin.job-family.create');
        })
            ->can(UserPermission::CREATE_JOB_FAMILY)
            ->name('create');
    });

    Route::prefix('job-title')->name('job-title.')->group(function () {
        Route::get('/', function () {
            return view('employee.admin.job-title.index');
        })
            ->name('index');

        Route::get('create', function () {
            return view('employee.admin.job-title.create');
        })
            ->can(UserPermission::CREATE_JOB_TITLE)
            ->name('create');
    });

    Route::prefix('job-board')->name('job-board.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.job-board.create');
        })->name('create');
    });


    /**
     * Calendar Manager
     */

    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('monthly', function () {
            return view('employee.admin.calendar.monthly');
        })
            ->name('monthly');

        Route::get('list', function () {
            return view('employee.admin.calendar.list');
        })
            ->name('list');
    });


    /**
     * Announcement
     */
    Route::prefix('announcement')->name('announcement.')->group(function () {
        Route::get('/', function () {
            return view('employee.admin.announcements.index');
        })
            ->name('index');

        Route::get('create', function () {
            return view('employee.admin.announcements.create');
        })
            ->can(UserPermission::CREATE_ANNOUNCEMENT)
            ->name('create');
    });

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
    Route::get('/applicants/{applicationStatus}', [ApplicationController::class, 'index'])
        ->where('applicationStatus', 'pending|qualified|preemployed')
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
            'permission:' . UserPermission::VIEW_APPLICATION_INFORMATION->value,
        ])
        ->middleware([
            'permission:' . UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value
                . '|' . UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value
                . '|' . UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value,
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



    Route::get('{employee}/attendance', [AttendanceController::class, 'show'])
        ->middleware(['permission:' . UserPermission::VIEW_ALL_DAILY_ATTENDANCE->value])
        ->middleware(['permission:' . UserPermission::VIEW_ALL_DAILY_ATTENDANCE->value])
        ->name('attendance.show');

    Route::get('/attendance/{range}', [AttendanceController::class, 'index'])
        ->middleware(['permission:' . UserPermission::VIEW_ALL_DAILY_ATTENDANCE->value])
        ->middleware(['permission:' . UserPermission::VIEW_ALL_DAILY_ATTENDANCE->value])
        ->where('range', 'daily|period')
        ->name('attendance.index');

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
     * Evaluator
     */
    Route::get('resume-evaluator/rankings', function () {
        return view('/employee.hr-manager.resume-evaluator.rankings');
    })->name('resume-evaluator.rankings');


    /**
     * PIP Generation
     */
    Route::prefix('performances')->name('performances.')->group(function () {

        Route::get('{performance}/plan/improvement/regulars/create', [RegularPerformancePlanController::class, 'create'])->name('plan.improvement.regular.create');

        Route::prefix('plan/improvement')->name('plan.improvement.')->group(function () {
            Route::get('/', function(){
                return view('employee.performance.improvement-plan.index');
            })
            ->can(UserPermission::VIEW_PLAN_GENERATOR)
            ->name('index');


                    /** Regulars */
            Route::prefix('regulars')->name('regular.')->group(function () {

                Route::get('/{pip}',  [RegularPerformancePlanController::class, 'show'])
                ->can(UserPermission::VIEW_PLAN_GENERATOR)
                ->name('generated');

                // trigger post request to external api google vertex
                Route::post('generate', [RegularPerformancePlanController::class, 'generate'])->name('generate');

                Route::post('save', [RegularPerformancePlanController::class, 'store'])->name('store');

                Route::patch('replace', [RegularPerformancePlanController::class, 'update'])->name('update');
            });

        });


    });




    /**
     * Performance
     */
    Route::prefix('performance')->name('performance.')->group(function () {
        Route::prefix('evaluation')->name('evaluation.')->group(function () {
            Route::get('/{employeeStatus}', [PerformanceDetailController::class, 'index'])
                ->where('employeeStatus', 'probationary|regular')
                ->name('index');
        });
    });


    /**
     * Performance Evaluation Results: Probationay
     */

    Route::get('evaluation-results/probationary', function () {
        return view('employee.hr-manager.evaluations.probationary.evaluation-results');
    })->name('evaluation-results.probationary');

    /**
     * Performance Evaluation Results: Regular
     */
    Route::get('evaluation-results/regular', function () {
        return view('/employee.hr-manager.evaluations.regular.evaluation-results');
    })->name('evaluation-results.regular');


    /**
     * Overtime resource
     *
     * TODO: Idk if Ivan plans to add middleware checks, but do them below.
     */
    Route::prefix('overtimes')->name('overtimes.')->group(function () {

        Route::get('requests/cut-offs', [OvertimeController::class, 'cutOff'])
            // ->can('viewOvertimeRequestAsInitialApprover')
            ->name('requests.cut-offs');

        Route::get('requests', [OvertimeController::class, 'authorize'])
            ->can('viewSubordinateOvertimeRequest')
            ->name('requests');

        Route::get('requests/summaries', [OvertimeController::class, 'requestSummary'])
            ->name('requests.summaries');

        Route::get('requests/{employee}/summaries', [OvertimeController::class, 'employeeSummary'])
            ->middleware('can:viewOvertimeSummary,employee')
            ->name('requests.employee.summaries');

        /** Ot requests summary */
        Route::get('/', [OvertimeController::class, 'index'])
            ->name('index');

        Route::get('/summaries', [OvertimeController::class, 'summary'])
            ->name('summaries');

        /** Ot requests recents */
        Route::get('recents', [OvertimeController::class, 'recent'])
            ->name('recents');

        /** Ot requests archive */
        Route::get('archive', [OvertimeController::class, 'archive'])
            ->name('archive');
    });


    /**
     * Leaves resource
     */
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])
            ->name('index');

        Route::get('balance', [LeaveController::class, 'myBalance'])
            ->name('balance');

        Route::get('balance/subordinates', [LeaveController::class, 'subordinateBalance'])
            ->name('balance.subordinates');

        Route::get('balance/general', [LeaveController::class, 'generalBalance'])
            ->name('balance.general');

        Route::get('overview', [LeaveController::class, 'request'])
            ->name('overview');

        Route::get('create', [LeaveController::class, 'create'])
            ->name('create');

        Route::get('{leave}', [LeaveController::class, 'show'])
            ->can('viewLeaveRequest', 'leave')
            ->whereNumber('leave')
            ->name('show');

        Route::get('requests', [LeaveController::class, 'request'])
            ->name('requests');

        Route::get('requests/general', [LeaveController::class, 'general'])
            ->name('requests.general');

        Route::get('{leave}/requests', [LeaveController::class, 'show'])
            // ->can('viewSubordinateLeaveRequest', 'leave')
            ->name('employee.requests');
    });


    /**
     * Employee relations resource
     */
    Route::prefix('relations')->name('relations.')->group(function () {
        /** Issue resource */
        Route::prefix('issues')->name('issues.')->group(function () {
            Route::get('/', [IssueController::class, 'index'])
                ->name('index');

            Route::get('create', [IssueController::class, 'create'])
                ->name('create');

            Route::get('{issue}', [IssueController::class, 'show'])
                ->can('viewIssueReport', 'issue')
                ->whereNumber('issue')
                ->name('show');

            Route::get('{attachment}/download', [IssueController::class, 'download'])
                ->name('download');

            Route::get('attachments/{attachment}', [IssueController::class, 'viewAttachment'])
                ->name('attachments.show');

            Route::get('general', [IssueController::class, 'general'])
                ->can('viewAnyIssueReport')
                ->name('general');

            Route::get('{issue}/review', [IssueController::class, 'review'])
                ->can('viewAnyIssueReport')
                ->name('review');
        });

        /** Incident resource */
        Route::prefix('incidents')->name('incidents.')->group(function () {
            Route::get('/', [IncidentController::class, 'index'])
                ->can('updateIncidentReport')
                ->name('index');

            Route::get('create', [IncidentController::class, 'create'])
                ->can('createIncidentReport')
                ->name('create');

            Route::get('{incident}', [IncidentController::class, 'show'])
                ->can('updateIncidentReport', 'incident')
                ->whereNumber('incident')
                ->name('show');

            Route::get('{attachment}/download', [IncidentController::class, 'download'])
                ->can('updateIncidentReport')
                ->name('download');

            Route::get('attachments/{attachment}', [IncidentController::class, 'viewAttachment'])
                ->can('updateIncidentReport')
                ->name('attachments.show');
        });
    });

    /** Performance resource */
    Route::prefix('performances')->name('performances.')->group(function () {
        Route::get('/', [PerformanceController::class, 'index'])
            ->name('index');

        Route::get('regular', [PerformanceController::class, 'asRegular'])
            ->name('regular');

        Route::get('regular/{performance}', [PerformanceController::class, 'showAsRegular'])
            ->name('regular.show');

        Route::get('probationary', [PerformanceController::class, 'asProbationary'])
            ->name('probationary');

        Route::get('probationary/{employee}', [PerformanceController::class, 'showAsProbationary'])
            ->name('probationary.show');

        /** Regulars */
        Route::prefix('regulars')->name('regulars.')->group(function () {
            Route::get('{employee}/create', [RegularPerformanceController::class, 'create'])
                ->can('evaluateRegularsPerformance', 'employee')
                ->name('create');

            Route::get('/', [RegularPerformanceController::class, 'index'])
                ->name('index');

            Route::get('{performance}', [RegularPerformanceController::class, 'show'])
                ->can('signAnyRegularEvaluationForm')
                ->whereNumber('performance')
                ->name('show');

            Route::get('general', [RegularPerformanceController::class, 'general'])
                ->name('general');

            Route::get('{performance}/review', [RegularPerformanceController::class, 'review'])
                ->can('signRegularEvaluationFormFinal')
                ->name('review');
        });

        /** Probationaries */
        Route::prefix('probationaries')->name('probationaries.')->group(function () {
            Route::get('{employee}/create', [ProbationaryPerformanceController::class, 'create'])
                ->can('evaluateProbationaryPerformance', 'employee')
                ->name('create');

            Route::get('/', [ProbationaryPerformanceController::class, 'index'])
                ->name('index');

            Route::get('{employee}', [ProbationaryPerformanceController::class, 'show'])
                ->whereNumber('employee')
                ->name('show');

            Route::get('general', [ProbationaryPerformanceController::class, 'general'])
                ->name('general');

            Route::get('{employee}/review', [ProbationaryPerformanceController::class, 'review'])
                ->can('signProbationaryEvaluationFormFinal')
                ->name('review');
        });
    });


    /** Training resource */
    Route::prefix('trainings')->name('trainings.')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])
            ->name('index');

        Route::get('{employee}', [TrainingController::class, 'show'])
            ->whereNumber('employee')
            ->name('employee');
    });


    /**
     * Employees
     */

    Route::get('/employees/all', function () {
        return view('employee.hr-manager.employees.all');
    })->name('employees.masterlist.all');

    Route::get('{employee}', function (Employee $employee) {
        return view('employee.hr-manager.employees.information', compact('employee'));
    })
        ->whereNumber('employee')
        ->name('employees.information');


    /**
     * Separation
     */
    Route::get('seperation/resignations', function () {
        return view('employee.hr-manager.separation.resignation.all');
    })->name('separation.resignations');

    Route::get('seperation/resignations/review', function () {
        return view('employee.hr-manager.separation.resignation.review');
    })->name('separation.resignations.review');

    Route::get('seperation/coe', function () {
        return view('employee.hr-manager.separation.coe.all');
    })->name('separation.coe');

    Route::get('seperation/coe/request', function () {
        return view('employee.hr-manager.separation.coe.request');
    })->name('separation.coe.request');

    /**
     * Reports
     */

    Route::get('reports', function () {
        return view('employee.hr-manager.reports.index');
    })->name('reports');

    /**
     * Archive
     */
    Route::get('/archive', function () {
        return view('employee.hr-manager.archive.index');
    })->name('employees.archive');

    Route::get('/archive/records', function () {
        return view('employee.hr-manager.archive.records');
    })->name('employees.archive.records');


    /**
     * General: Dashboard
     */
    Route::get('/index', [EmployeeController::class, 'index'])
        ->name('index');


    /**
     * General: Attendance
     */
    Route::get('/attendance', function () {
        return view('employee.basic.attendance.index');
    })->name('attendance');


    /**
     * Payslip resource
     */
    Route::prefix('payslips')->name('payslips.')->group(function () {
        Route::get('/', [PayslipController::class, 'index'])
            ->name('index');

        Route::get('attachments/{attachment}', [PayslipController::class, 'viewAttachment'])
            ->name('attachments.show');

        Route::get('{attachment}/download', [PayslipController::class, 'download'])
            ->name('attachments.download');

        Route::get('general', [PayslipController::class, 'general'])
            ->name('general');

        Route::get('bulk-upload', [PayslipController::class])
            ->name('bulk');
    });


    /**
     * File Manager Resource
     */
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('contracts', [FileManagerController::class, 'contracts'])
            ->name('contracts');

        Route::get('contracts/{attachment}', [FileManagerController::class, 'viewContractAttachment'])
            ->name('contracts.attachments');

        Route::get('pre-employments', [FileManagerController::class, 'preEmployments'])
            ->name('pre-employments');

        Route::get('trainings', [FileManagerController::class, 'trainings'])
            ->name('trainings');

        Route::get('incidents', [FileManagerController::class, 'incidents'])
            ->name('incidents');

        Route::get('issues', [FileManagerController::class, 'issues'])
            ->name('issues');

        Route::get('leaves', [FileManagerController::class, 'leaves'])
            ->name('leaves');
    });

    /**
     * General: Separation
     */
    Route::get('/separation/index', function () {
        return view('employee.basic.separation.index');
    })->name('separation.index');

    Route::get('/separation/file-resignation', function () {
        return view('employee.basic.separation.file-resignation');
    })->name('separation.file-resignation');
});

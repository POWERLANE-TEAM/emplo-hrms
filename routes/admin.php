<?php

use App\Enums\UserPermission;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\EmployeeArchiveController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth')->group(function () {

    /**
     * Recycle Bin
     */
    Route::get('recycle-bin', function () {
        return view('employee.recycle-bin.index');
    })->name('recycle-bin');

    Route::get('notifications', function () {
        return view('employee.notifications.index');
    })->name('notifications');

    /**
     * Dashboard
     */
    Route::get('dashboard', DashboardController::class)
        ->can(UserPermission::VIEW_ADMIN_DASHBOARD)
        ->name('dashboard');

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
     * Log out
     */
    Route::post('logout', [Logout::class, 'destroy'])
        ->name('logout');


    /**
     * Laravel Pulse
     */
    Route::get('system/pulse', function () {
        return view('vendor.pulse.dashboard');
    })->name('system.pulse');

    /**
     * Accounts
     */
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', function () {
            return view('employee.admin.accounts.index');
        })
            ->can(UserPermission::VIEW_ALL_ACCOUNTS)
            ->name('index');

        Route::get('create', function () {
            return view('employee.admin.accounts.create');
        })
            ->can(UserPermission::CREATE_EMPLOYEE_ACCOUNT)
            ->name('create');
    });


    /** 
     * Employee resource 
     */
    Route::get('list', [EmployeeController::class, 'index'])
        ->can('viewAnyEmployees')
        ->name('employees.masterlist.all');

    Route::get('{employee}', [EmployeeController::class, 'show'])
        ->can('viewEmployee')
        ->whereNumber('employee')
        ->name('employees.information');


    /**
     * Archive
     */
    Route::prefix('archives')->name('archives.')->group(function () {
        Route::get('/', [EmployeeArchiveController::class, 'index'])
            ->can('viewAnyArchivedRecords')
            ->name('index');

        Route::get('{employee}', [EmployeeArchiveController::class, 'show'])
            ->can('viewAnyArchivedRecords')
            ->name('employee');
    });


    /**
     * Job Family
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

    /**
     * Job Title
     */
    Route::prefix('job-titles')->name('job-titles.')->group(function () {
        Route::get('/', function () {
            return view('employee.admin.job-title.index');
        })
            ->name('index');

        Route::get('create', function () {
            return view('employee.admin.job-title.create');
        })
            ->can(UserPermission::CREATE_JOB_TITLE)
            ->name('create');

        Route::get('{jobTitle}', [JobTitleController::class, 'show'])
            ->whereNumber('jobTitle')
            ->name('show');
    });

    /**
     * Calendar
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
     * Job Listings
     */
    Route::prefix('job-board')->name('job-board.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.job-board.create');
        })->name('create');
    });


    /**
     * Announcement
     */
    Route::prefix('announcements')->name('announcements.')->group(function () {
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
     * Configuration
     * 
     * Performance & Forms
     */
    Route::prefix('config')->name('config.')->group(function () {
        /**
         * Performance Config
         */
        Route::prefix('performance')->name('performance.')->group(function () {

            // Categories
            Route::get('categories', function () {
                return view('employee.admin.config.performance.categories');
            })->name('categories');

            // Rating Scales
            Route::get('rating-scales', function () {
                return view('employee.admin.config.performance.rating-scales');
            })->name('rating-scales');

            // Period Set-Up
            Route::get('period-setup', function () {
                return view('employee.admin.config.performance.period-setup');
            })->name('period-setup');
        });

        /**
         * Forms Config
         */
        Route::prefix('form')->name('form.')->group(function () {

            // Pre-Employment
            Route::get('pre-employment', function () {
                return view('employee.admin.config.form.pre-employment');
            })->name('pre-employment');
        });
    });

    /**
     * Attendance
     */
    Route::middleware('can:' . UserPermission::UPDATE_BIOMETRIC_DEVICE->value)
        ->prefix('attendance')->name('attendance.')->group(function () {

            // Biometric Device Manager
            Route::get('biometric-device', function () {
                return view('employee.admin.attendance.biometric-device');
            })->name('biometric-device');

            // Attendance Logs
            Route::get('logs', function () {
                return view('employee.admin.attendance.index');
            })->name('logs');
        });
});

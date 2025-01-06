<?php

use App\Enums\UserPermission;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth')->group(function () {

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
     * Profile
     */
    Route::get('profile', function () {
        return view('employee.profile.information.index');
    })->name('profile');

    Route::get('profile/edit', function () {
        return view('employee.profile.information.edit');
    })->name('profile.edit');

    /**
     * Settings & Privacy
     */
    Route::get('settings', function () {
        return view('employee.profile.settings');
    })->name('settings');

    Route::get('activity-logs', function () {
        return view('employee.profile.activity-logs');
    })->name('activity-logs');

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

    /**
     * Calendar
     */
    Route::get('calendar', function () {
        return view('employee.admin.calendar');
    })->name('calendar');


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
    Route::prefix('announcement')->name('announcement.')->group(function () {
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

            // Scorings
            Route::get('scorings', function () {
                return view('employee.admin.config.performance.scorings');
            })->name('scorings');
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
    Route::middleware('can:'.UserPermission::UPDATE_BIOMETRIC_DEVICE->value)
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

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

    // Dashboard
    // ----------------------------------
    Route::get('dashboard', DashboardController::class)
        ->can(UserPermission::VIEW_ADMIN_DASHBOARD)
        ->name('dashboard');

    // Employee Profile
    // ----------------------------------
    Route::get('profile', function () {
        return view('employee.admin.profile');
    })->name('profile');

    // Log Out
    // ----------------------------------
    Route::post('logout', [Logout::class, 'destroy'])
        ->name('logout');

    // Laravel Pulse
    // ----------------------------------
    Route::get('system/pulse', function () {
        return view('vendor.pulse.dashboard');
    })->name('system.pulse');

    // View Accounts
    // ----------------------------------
    Route::get('accounts', function () {
        return view('employee.admin.accounts.accounts');
    })->name('accounts');

    // Create Accounts
    // ----------------------------------
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.accounts.create');
        })
            ->can(UserPermission::CREATE_EMPLOYEE_ACCOUNT)
            ->name('create');
    });

    // Create Job Family
    // ----------------------------------
    Route::prefix('job-family')->name('job-family.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.job-family.create');
        })
            ->can(UserPermission::CREATE_JOB_FAMILY)
            ->name('create');
    });

    // Create Job Title
    // ----------------------------------
    Route::prefix('job-title')->name('job-title.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.job-title.create');
        })
            ->can(UserPermission::CREATE_JOB_TITLE)
            ->name('create');
    });

    // Calendar
    // ----------------------------------
    Route::get('calendar', function () {
        return view('employee.admin.calendar');
    })->name('calendar');

    // Create Open Job Title
    // ----------------------------------
    Route::prefix('job-board')->name('job-board.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.job-board.create');
        })->name('create');
    });

    // Create Announcement
    // ----------------------------------
    Route::prefix('announcement')->name('announcement.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.announcements.create');
        })
            ->can(UserPermission::CREATE_ANNOUNCEMENT)
            ->name('create');
    });

    // =========================================
    // CONFIGURATION: PERF. & FORMS
    // ==========================================

    Route::prefix('config')->name('config.')->group(function () {

        // Performance Eval. Configuration
        // ----------------------------------
        // Categories, rating and scores config.

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

        // Forms Configuration
        // --------------------------
        // All forms of the system.

        Route::prefix('form')->name('form.')->group(function () {

            // Pre-Employment
            Route::get('pre-employment', function () {
                return view('employee.admin.config.form.pre-employment');
            })->name('pre-employment');
        });
    });

});

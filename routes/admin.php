<?php

use App\Enums\UserPermission;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->can(UserPermission::VIEW_ADMIN_DASHBOARD)
        ->name('dashboard');

    Route::get('system/pulse', function () {
        return view('vendor.pulse.dashboard');
    })->name('system.pulse');

    Route::get('accounts', function () {
        return view('employee.admin.accounts.accounts');
    })->name('accounts');
        
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.accounts.create');
        })
            ->can(UserPermission::CREATE_EMPLOYEE_ACCOUNT)
            ->name('create');
    });

    Route::prefix('job-family')->name('job-family.')->group(function () {
        Route::get('create', function() {
            return view('employee.admin.job-family.create');
        })
            ->can(UserPermission::CREATE_JOB_FAMILY)
            ->name('create');        
    });

    Route::prefix('job-title')->name('job-title.')->group(function () {
        Route::get('create', function() {
            return view('employee.admin.job-title.create');
        })
            ->can(UserPermission::CREATE_JOB_TITLE)
            ->name('create');
    });

    Route::get('calendar', function() {
       return view('employee.admin.calendar');
    })->name('calendar');

    Route::prefix('job-board')->name('job-board.')->group(function () {
        Route::get('create', function() {
            return view('employee.admin.jobboard.add-open-position');
        })->name('create');        
    });

    Route::get('announcements', function () {
        return view('employee.admin.announcements.announcements');
    })->name('announcement');

    Route::prefix('announcement')->name('announcement.')->group(function () {
        Route::get('create', function () {
            return view('employee.admin.announcements.create-announcement');
        })
            ->can(UserPermission::CREATE_ANNOUNCEMENT)
            ->name('create');        
    });

    Route::prefix('config')->name('config.')->group(function () {
        Route::prefix('performance')->name('performance.')->group(function () {
            Route::get('categories', function() {
                return view('employee.admin.config.performance.categories');
            })->name('categories');
            
            Route::get('rating-scales', function() {
                return view('employee.admin.config.performance.rating-scales');
            })->name('rating-scales');
            
            Route::get('scorings', function() {
                return view('employee.admin.config.performance.scorings');
            })->name('scorings');
        });

        Route::prefix('form')->name('form.')->group(function () {
            Route::get('pre-employment', function() {
                return view('employee.admin.config.form.pre-employment');
            })->name('pre-employment');            
        });
    });

    Route::get('profile', function () {
        return view('employee.admin.profile');
    })->name('profile');

    Route::post('logout', [Logout::class, 'destroy'])
        ->name('logout');
});

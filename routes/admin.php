<?php

use App\Enums\UserPermission;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth:admin')->group(function () {

    // Dashboard
    Route::get('dashboard', DashboardController::class)
        ->can(UserPermission::VIEW_ADMIN_DASHBOARD)
        ->name('dashboard');

    Route::get('system/pulse', function() {
        return view('vendor.pulse.dashboard');
    })->name('system.pulse');

    
    // -- Accounts Routes --
    Route::get('accounts', function() {
        return view('employee.admin.accounts.accounts');
    })->name('accounts'); 

    Route::get('create-account', function() {
        return view('employee.admin.accounts.create-account');
    })->name('create-account'); 
    // End of Accounts


    // -- Organization Routes --
    Route::get('create-dept', function() {
        return view('employee.admin.organization.create-dept');
    })->name('create-dept');

    Route::get('create-position', function() {
        return view('employee.admin.organization.create-position');
    })->name('create-position');
    // End of Organization
    

    Route::get('calendar', function() {
        abort(404);
    })->name('calendar');


    Route::get('add-open-position', function() {
        return view('employee.admin.jobboard.add-open-position');
    })->name('add-open-position');


    Route::get('policy', function() {
        abort(404);
    })->name('policy');


    // -- Announcements Routes --
    Route::get('announcements', function() {
        return view('employee.admin.announcements.announcements');
    })->name('announcement');

    Route::get('create-announcement', function() {
        return view('employee.admin.announcements.create-announcement');
    })->name('create-announcement');
    // End of Announcements


    Route::get('performance', function() {
        abort(404);
    })->name('performance');


    // -- Forms Routes --
    Route::get('pre-emp-reqs', function() {
        return view('employee.admin.forms.pre-emp-reqs');
    })->name('pre-emp-reqs');
    // End of Forms

    Route::get('profile', function() {
        return view('employee.admin.profile');
    })->name('profile');
});

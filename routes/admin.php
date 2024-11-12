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

    // Dashboard
    Route::get('dashboard', DashboardController::class)
        ->can(UserPermission::VIEW_ADMIN_DASHBOARD)
        ->name('dashboard');

    Route::get('system/pulse', function () {
        return view('vendor.pulse.dashboard');
    })->name('system.pulse');


    // -- Accounts Routes --
    Route::get('accounts', function () {
        return view('employee.admin.accounts.accounts');
    })->name('accounts');

    Route::get('create-account', function () {
        return view('employee.admin.accounts.create-account');
    })->can(UserPermission::CREATE_EMPLOYEE_ACCOUNT)
        ->name('create-account'); 
    // End of Accounts


    // -- Organization Routes --
    Route::get('create-job-family', function() {
        return view('employee.admin.organization.create-job-family');
    })->name('create-job-family');

    Route::get('create-job-title', function() {
        return view('employee.admin.organization.create-job-title');
    })->name('create-job-title');
    // End of Organization
    

    Route::get('calendar', function() {
       return view('employee.admin.calendar');
    })->name('calendar');


    Route::get('add-open-position', function() {
        return view('employee.admin.jobboard.add-open-position');
    })->name('add-open-position');


    Route::get('policy', function () {
        abort(404);
    })->name('policy');


    // -- Announcements Routes --
    Route::get('announcements', function () {
        return view('employee.admin.announcements.announcements');
    })->name('announcement');

    Route::get('create-announcement', function () {
        return view('employee.admin.announcements.create-announcement');
    })->name('create-announcement');
    // End of Announcements


    // -- Performance Eval Routes --
    Route::get('categories', function() {
        return view('employee.admin.performance.categories');
    })->name('categories');

    Route::get('pass-rate-range', function() {
        return view('employee.admin.performance.pass-rate-range');
    })->name('pass-rate-range');

    Route::get('perf-scales', function() {
        return view('employee.admin.performance.perf-scales');
    })->name('perf-scales');

    Route::get('scoring', function() {
        return view('employee.admin.performance.scoring');
    })->name('scoring');
    // End of Performance Eval Routes

    // -- Forms Routes --
    Route::get('pre-emp-reqs', function() {
        return view('employee.admin.forms.pre-emp-reqs');
    })->name('pre-emp-reqs');
    // End of Forms

    Route::get('profile', function () {
        return view('employee.admin.profile');
    })->name('profile');

    Route::post('logout', [Logout::class, 'destroy'])
        ->name('logout');
});

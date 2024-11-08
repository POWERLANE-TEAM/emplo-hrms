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


    Route::get('employees', function () {
        abort(404);
    })->name('employees');


    Route::get('calendar', function() {
       return view('employee.admin.calendar');
    })->name('calendar');


    Route::get('job-listing', function () {
        abort(404);
    })->name('job-listing');


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


    Route::get('performance', function () {
        abort(404);
    })->name('performance');


    Route::get('form', function () {
        abort(404);
    })->name('form');

    Route::get('profile', function () {
        return view('employee.admin.profile');
    })->name('profile');

    Route::post('logout', [Logout::class, 'destroy'])
        ->name('logout');
});

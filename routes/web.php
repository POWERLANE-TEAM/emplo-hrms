<?php

use App\Livewire\Auth\GoogleOAuth;
use App\Livewire\Auth\GoogleOneTap;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ApplicantDocController;
use App\Http\Controllers\PreEmploymentController;

Route::get('/apply', function () {
    return view('apply');
});

Route::middleware('')->group(function () {
    Route::get('/hiring', function () {
        return view('hiring');
    });
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/applicant', [ApplicantDocController::class, 'index']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/preemploy',  [PreEmploymentController::class, 'create']);
    Route::post('/preemploy', [PreEmploymentController::class, 'store']);
});

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleOAuth::class, 'googleOauth'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [GoogleOAuth::class, 'googleCallback'])
        ->name('auth.google.callback');
    Route::post('/auth/googleonetap/callback', [GoogleOneTap::class, 'handleCallback'])
        ->name('auth.googleonetap.callback');
});


/*
 * use for testing authorization in RouteMiddlewareAuthorizationTest class
 */

Route::get('/fake-uri1', function () {
    return view('temp.route-middleware-auth');
})->middleware('permission:' . App\Enums\UserPermission::VIEW_EMPLOYEE_DASHBOARD->value);

Route::get('/fake-uri2', function () {
    return view('temp.route-middleware-auth');
})->middleware('permission:' . App\Enums\UserPermission::VIEW_EMPLOYEE_INFORMATION->value);

Route::get('/fake-uri3', function () {
    return view('temp.route-middleware-auth');
})->middleware('permission:' . App\Enums\UserPermission::DELETE_JOB_LISTING->value);

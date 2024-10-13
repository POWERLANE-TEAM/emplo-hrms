<?php

use App\Enums\UserPermission;
use App\Http\Controllers\ApplicantDocController;
use App\Http\Controllers\PreEmploymentController;
use App\Livewire\Auth\FacebookOAuth;
use App\Livewire\Auth\GoogleOAuth;
use App\Livewire\Auth\GoogleOneTap;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Route;

Route::get('/apply', function () {
    return view('apply');
});

Route::group([], function () {
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
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [GoogleOAuth::class, 'googleCallback']);

    Route::post('/auth/googleonetap/callback', [GoogleOneTap::class, 'handleCallback']);

    Route::get('/auth/facebook/redirect', [FacebookOAuth::class, 'facebookOauth'])
        ->name('auth.facebook.redirect');

    Route::get('/auth/facebook/callback', [FacebookOAuth::class, 'handleCallback']);
});

Route::post('/web/logout', [Logout::class, 'destroy'])
    ->middleware('auth:web')
    ->name('web.logout');

/*
 * use for testing authorization in RouteMiddlewareAuthorizationTest class
 */

Route::get('/fake-uri1', function () {
    return view('temp.route-middleware-auth');
})->middleware('permission:' . UserPermission::VIEW_EMPLOYEE_DASHBOARD->value);

Route::get('/fake-uri2', function () {
    return view('temp.route-middleware-auth');
})->middleware('permission:' . UserPermission::VIEW_EMPLOYEE_INFORMATION->value);

Route::get('/fake-uri3', function () {
    return view('temp.route-middleware-auth');
})->middleware('permission:' . UserPermission::DELETE_JOB_LISTING->value);

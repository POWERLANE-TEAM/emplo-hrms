<?php

use App\Livewire\Auth\GoogleOAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ApplicantDocController;
use App\Http\Controllers\PreEmploymentController;

Route::get('/', function () {
    return view('index');
}); // this should be change into a component when about and contact components are created

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/applicant', [ApplicantDocController::class, 'index']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/preemploy', [PreEmploymentController::class, 'create']);
    Route::post('/preemploy', [PreEmploymentController::class, 'store']);
});

Route::get('/employee/{page?}', [EmployeeController::class, 'employee'])->middleware(['auth', 'verified']);

Route::get('api/json/{requestedData}', [JsonController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleOAuth::class, 'googleOauth'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [GoogleOAuth::class, 'googleCallback'])
        ->name('auth.google.callback');
});


/*
 * use for testing authorization in RouteMiddlewareAuthorizationTest class 
 */

Route::get('/fake-uri1', function() {
    return view ('temp.route-middleware-auth');
})->middleware('permission:' . App\Enums\UserPermission::VIEW_EMPLOYEE_DASHBOARD->value);

Route::get('/fake-uri2', function() {
    return view ('temp.route-middleware-auth');
})->middleware('permission:' . App\Enums\UserPermission::VIEW_EMPLOYEE_INFORMATION->value);

Route::get('/fake-uri3', function() {
    return view ('temp.route-middleware-auth');
})->middleware('permission:' . App\Enums\UserPermission::DELETE_JOB_LISTING->value);
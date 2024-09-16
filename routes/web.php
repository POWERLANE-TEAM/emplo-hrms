<?php

use App\Http\Controllers\ApplicantDocController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\PreEmploymentController;
use App\Http\Middleware\Localization;
use App\Livewire\Auth\GoogleOAuth;
use App\Livewire\Auth\GoogleOneTap;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/apply', function () {
    return view('apply');
});

Route::/* prefix('hr')-> *//* middleware('auth:hr')-> */group([], function () {
    //
});

Route::get('/hiring', function () {
    return view('hiring');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/applicant', [ApplicantDocController::class, 'index']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/preemploy',  [PreEmploymentController::class, 'create']);
    Route::post('/preemploy', [PreEmploymentController::class, 'store']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // MANAGES WICH VIEW OF DASHBOARD
    Route::get('/dashboard',  [DashboardController::class, 'index']);
});

Route::get('/employee/{page?}', [EmployeeController::class, 'employee'])->middleware(['auth', 'verified']);

Route::get('api/json/{requestedData}', [JsonController::class, 'index']);

/*
|--------------------------------------------------------------------------
| carl routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleOAuth::class, 'googleOauth'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [GoogleOAuth::class, 'googleCallback'])
        ->name('auth.google.callback');
});


Route::post('auth/googleonetap/callback', [GoogleOneTap::class, 'handleCallback']);

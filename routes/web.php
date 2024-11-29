<?php

use App\Http\Controllers\Application\ApplicantController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PreEmploymentController;
use App\Http\Controllers\ResumeController;
use App\Livewire\Auth\FacebookOAuth;
use App\Livewire\Auth\GoogleOAuth;
use App\Livewire\Auth\GoogleOneTap;
use App\Livewire\Auth\Logout;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::group([], function () {
    Route::get('/hiring', function () {
        return view('hiring');
    });
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/application/{page?}', [ApplicantController::class, 'index']);

    Route::get('/preemploy', [PreEmploymentController::class, 'create']);
    Route::post('/preemploy', [PreEmploymentController::class, 'store']);
});

Route::get('/apply', [ApplicantController::class, 'create']);

Route::post('/resume/process', [DocumentController::class, 'recognizeText'])
    ->name('resume.process');

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::get('/auth/google/redirect', [GoogleOAuth::class, 'googleOauth'])
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [GoogleOAuth::class, 'googleCallback']);

    Route::post('/auth/googleonetap/callback', [GoogleOneTap::class, 'handleCallback']);

    Route::get('/auth/facebook/redirect', [FacebookOAuth::class, 'facebookOauth'])
        ->name('auth.facebook.redirect');

    Route::get('/auth/facebook/callback', [FacebookOAuth::class, 'handleCallback']);
});

Route::post('/logout', [Logout::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

<?php

use App\Http\Controllers\ApplicantDocController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JsonController;
use App\Livewire\Auth\GoogleOAuth;
use App\Livewire\Auth\GoogleOneTap;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('index');
}); // this should be change into a component when about and contact components are created


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/applicant', [ApplicantDocController::class, 'index']);
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
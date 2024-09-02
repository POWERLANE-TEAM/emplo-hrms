<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JsonController;
use App\Livewire\GoogleOAuth;
use App\Livewire\Guest\Forms\SignUp;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
}); // this should be change into a controller when about and contact components are created

Route::get('/applicants/apply/{applyPage}', [ApplicantController::class, 'apply']);
Route::get('/applicants/apply', [ApplicantController::class, 'apply']);

Route::get('/employee/{page}', [EmployeeController::class, 'employee']);
Route::get('/employee', [EmployeeController::class, 'employee']);

Route::get('api/json/{requestedData}',  [JsonController::class, 'index']);

/*
|--------------------------------------------------------------------------
| carl routes
|--------------------------------------------------------------------------
|
*/

// Route::get('/signup', [SignUp::class, 'render']);
Route::get('/auth/google/redirect', [GoogleOAuth::class, 'googleOauth'])
    ->name('auth.google');
Route::get('/auth/google/callback', [GoogleOAuth::class, 'googleCallback'])
    ->name('auth.google.callback');
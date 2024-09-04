<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JsonController;
use App\Livewire\GoogleOAuth;
use App\Livewire\Guest\Forms\SignUp;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/email/verify', function () {
//     return 'Test route works!';
// })->name('verification.notice');

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     return redirect('/home');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();

//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/', function () {
    return view('index');
}); // this should be change into a controller when about and contact components are created

// Route::get('/login', function () {
//     return view('login');
// })->name('login');







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

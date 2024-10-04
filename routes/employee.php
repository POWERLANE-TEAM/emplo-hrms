<?php

use App\Http\Controllers\Employee\DashboardController;
use App\Livewire\Auth\Employees\Login;
use App\Livewire\Auth\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

// Route::prefix('employee')->/* middleware('auth:employee')-> */name('employee.')->group(function () {

//     Route::view('/login', 'livewire.auth.employee.login')->name('login');

//     $limiter = config('fortify.limiters.login');
//     // $twoFactorLimiter = config('fortify.limiters.two-factor');
//     // $verificationLimiter = config('fortify.limiters.verification', '6,1');

//     Route::post(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'store'])
//         ->middleware(array_filter([
//             'guest:employee',
//             $limiter ? 'throttle:' . $limiter : null,
//         ]));
// });

Route::middleware('guest:employee')->group(function () {
    // Route::get('/login',  function () {
    //     return view('livewire.auth.employees.login-view');
    // })->name('login');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
});

Route::middleware('auth:employee'/* , 'verified' */)->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');
    Route::get('/sample',  function () {
        dd(request());
        echo 'sample';
    });
    Route::post('/logout', [Logout::class, 'destroy'])
        ->name('logout');
});

<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JsonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/applicants/apply/{applyPage}', [ApplicantController::class, 'apply']);
Route::get('/applicants/apply', [ApplicantController::class, 'apply']);

Route::get('/employee/{page}', [EmployeeController::class, 'employee']);
Route::get('/employee', [EmployeeController::class, 'employee']);

Route::get('api/json/{requestedData}',  [JsonController::class, 'index']);

<?php

use App\Http\Controllers\ApplicantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/applicants/apply/{applyPage}', [ApplicantController::class, 'apply']);
Route::get('/applicants/apply', [ApplicantController::class, 'apply']);

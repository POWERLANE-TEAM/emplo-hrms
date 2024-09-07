<?php

use Google;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;
use App\Livewire\Guest\Auth\GoogleOAuth;
use App\Livewire\Guest\Auth\GoogleOneTap;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ApplicantController;

Route::get('/', function () {
    return view('index');
}); // this should be change into a controller when about and contact components are created



Route::get('/applicants/apply/{applyPage}', [ApplicantController::class, 'apply']);
Route::get('/applicants/apply', [ApplicantController::class, 'apply']);

Route::get('/employee/{page?}', [EmployeeController::class, 'employee'])->middleware(['auth', 'verified']);


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

// Route::post('/auth/googleonetap/callback', [GoogleOneTap::class, 'handle'])
//     ->name('auth/googleonetap/callback');

Route::post('/auth/googleonetap/callback', function() {
    $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
    $payload = $client->verifyIdToken($_POST['credential']);

    if ($payload) {
        $findUser = \App\Models\User::where('email', $payload['email'])->first();

        if ($findUser) {
            dd($payload);
        } else {
            dd($payload);
        }
    }  
});

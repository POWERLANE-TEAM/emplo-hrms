<?php

use App\Http\Controllers\Application\ApplicantController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ApplicationDocController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\WebThemeController;
use App\Livewire\Auth\FacebookOAuth;
use App\Livewire\Auth\GoogleOAuth;
use App\Livewire\Auth\GoogleOneTap;
use App\Livewire\Auth\Logout;
use Google\Cloud\AIPlatform\V1\Client\ModelServiceClient;
use Google\Cloud\AIPlatform\V1\ListModelsRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


Route::group([], function () {
    Route::get('/hiring', function () {
        return view('hiring');
    })->name('hiring');
});

Route::post('/theme-preference/set', [WebThemeController::class, 'create'])
    ->middleware('throttle:4,1');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/application/{application?}', [ApplicantController::class, 'show'])->name('applicant.dashboard');

    Route::get('/apply/{job}', [ApplicantController::class, 'create'])->name('apply');

    Route::get('/preemploy', [ApplicationDocController::class, 'create']);
    Route::post('/preemploy', [ApplicationDocController::class, 'store']);
});


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

Route::get('/test-pop-ups', function () {
    return view('components.html.test-pop-ups');
});

Route::get('/information-centre', function () {
    return view('help-centre.index');
});

Route::get('/modal-content/{modalKey}', [ContentController::class, 'getModalContent']);

Route::get('/forgot-password', function () {
    return view('password-recovery.index');
});

Route::get('/vertex/list', function () {

    // adds logging shomn at the top of the paga
    if (app()->environment('local')) {
        putenv('GOOGLE_SDK_PHP_LOGGING=true');
    }

    if (!Storage::exists('services/credentials/vertex-ai.json')) {
        return 'File not found';
    }

    $credentialsPath = 'services/credentials/vertex-ai.json';


    $clientOptions = [
        'apiEndpoint' => 'us-central1-aiplatform.googleapis.com:443',
        'credentials' => Storage::path($credentialsPath),
    ];

    $client = new ModelServiceClient($clientOptions);

    putenv('GOOGLE_APPLICATION_CREDENTIALS=Storage::path($credentialsPath)');

    $request = new ListModelsRequest();
    $request->setParent('projects/1003638908095/locations/us-central1');

    $response = $client->listModels($request);


    dd($response);
});

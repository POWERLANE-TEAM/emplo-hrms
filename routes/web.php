<?php

use App\Enums\FilePath;
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
use App\Traits\NeedsWordDocToPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Google\Cloud\AIPlatform\V1\Client\ModelServiceClient;
use Google\Cloud\AIPlatform\V1\ListModelsRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use NcJoes\OfficeConverter\OfficeConverter;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Paper;
use PhpOffice\PhpWord\TemplateProcessor;

Route::group([], function () {
    Route::get('/hiring', function () {
        return view('hiring');
    })->name('hiring');
});

Route::get('/', function () {
    return view('landing');
})->name('landing');

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

Route::get('/guest/information-centre', function () {
    return view('help-centre.guest');
});

Route::get('/information-centre', function () {
    return view('help-centre.index');
});

Route::get('/modal-content/{modalKey}', [ContentController::class, 'getModalContent']);

Route::get('/forgot-password', function () {
    return view('password-recovery.index');
});

Route::get('/canvas', function () {
    return view('canvas');
});

Route::get('/pdf', function () {

    $coeData = [
        'name' =>  'John Doe',
        'empStart' => '2021-01-01',
        'empEnd' =>  '2021-12-31',
        'jobTitle'  => 'Software Developer',
        'jobDepartment'  => 'IT',
        'issuedDate'  => now(),
        'hrManager' =>  'Jane Doe',
        'companyAddr' => 'Rowsuz Business Center, Diversin Rd',
    ];

    $options = [
        // 'debugPng' => true,
        // 'debugCss' => true,
        // 'debugLayout' => true,
        // 'debugLayoutLine' => true,
        // 'debugLayoutPaddingBox' => true,
    ];

    $coe = Pdf::loadView('coe', $coeData);
    $coe->setPaper('a4', 'landscape');
    $coe->setOptions($options);
    return $coe->download('coe.pdf');
});

Route::get('/coe', function () {
    return view('coe');
});
Route::get('/word', function () {
    $reader = IOFactory::createReader('Word2007');

    $path = FilePath::DOC_TEMPLATE->value . 'Certificate of Appreciation.docx';

    // echo $path;

    if (Storage::disk('public')->missing($path)) {
        abort(404);
    }

    $templateProcessor = new TemplateProcessor(Storage::disk('public')->path($path));

    $values = [
        // Long Names isnt shrinked
        'EMPLOYEE_NAME' => 'John Doe',
        'START_DATE' => 'January 1, 2020',
        'END_DATE' => 'December 31, 2024',
        'JOB_TITLE' => 'Software Engineer',
        'DEPT_NAME' => 'IT Department',
        'ORDINAL' => '7th',
        'MONTH' => 'February',
        'YEAR' => '2025',
        'COMPANY_ADDRESS' => '1234 Main St, Calamba, Calabarzon, Philippines',
        'HRManager_NAME' => 'John Doe',
    ];

    $templateProcessor->setValues($values);
    $templateProcessor->setImageValue('USER_SIGNATURE', 'C:\Users\Summer\Downloads\evangeline_bandilla-sign.png' );

    $docxFilePath = 'word.docx';
    $templateProcessor->saveAs(Storage::disk('public')->path($docxFilePath));

    $converter = new class {
        use NeedsWordDocToPdf;
    };

    $outputDir = 'pdf';
    $disk = 'public';

    try {
        $pdfFilePath = $converter->convert($docxFilePath, $outputDir, $disk);
        return response()->download(Storage::disk($disk)->path($pdfFilePath));
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

});

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
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Cloud\AIPlatform\V1\Client\PredictionServiceClient;
use Google\Cloud\AIPlatform\V1\Client\ModelServiceClient;
// use Google\Cloud\AIPlatform\V1\Client\ModelServiceClient;
// use Google\Cloud\AIPlatform\V1\PredictRequest;
use Google\Cloud\AIPlatform\V1\GenerateContentRequest;
use Google\Cloud\AIPlatform\V1\ListModelsRequest;
use Google\Cloud\AIPlatform\V1\Content;
use Google\Cloud\AIPlatform\V1\Part;
use Google\Protobuf\Struct;
use Google\Protobuf\Value;
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


Route::get('/vertex', function () {

    // adds logging shomn at the top of the paga
    if (app()->environment('local')) {
        putenv('GOOGLE_SDK_PHP_LOGGING=true');
    }

    if (!Storage::exists('services/credentials/vertex-ai.json')) {
        return 'File not found';
    }

    $credentialsPath = 'services/credentials/vertex-ai.json';

    // default prefix of endpoint will be aiplatform.googleapis.com
    // no custom endpoints prefix = response Error 404 page
    $clientOptions = [
        // results { "message": "This endpoint is a dedicated endpoint via CloudESF and cannot be accessed through the Vertex AI API. Please access the endpoint using its dedicated dns name '{ENDPOINT_ID}.us-central1-{PROJECT_NUMBER}.prediction.vertexai.goog'", "code": 9, "status": "FAILED_PRECONDITION", "details": [] }
        'apiEndpoint' => 'us-central1-aiplatform.googleapis.com:443',

        // results cURL error 6: Could not resolve host: 6904274414968242176.us-central1-1003638908095.prediction.vertexai.goog (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://{ENDPOINT_ID}.us-central1-{PROJECT_NUMBER}.prediction.vertexai.goog/v1/projects/emplo-ocr/locations/us-central1/endpoints/{ENDPOINT_ID}:generateContent?%24alt=json%3Benum-encoding%3Dint
        // 'apiEndpoint' => '6904274414968242176.us-central1-1003638908095.prediction.vertexai.goog',

        // results cURL error 60: SSL: no alternative certificate subject name matches target host name '{ENDPOINT_ID}.us-central1-aiplatform.googleapis.com' (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://{ENDPOINT_ID}.us-central1-aiplatform.googleapis.com/v1/projects/emplo-ocr/locations/us-central1/endpoints/{ENDPOINT_ID}:generateContent?%24alt=json%3Benum-encoding%3Dint
        // 'apiEndpoint' => '6904274414968242176.us-central1-aiplatform.googleapis.com',

        'credentials' => Storage::path($credentialsPath),
    ];

    $client = new PredictionServiceClient($clientOptions);

    putenv('GOOGLE_APPLICATION_CREDENTIALS=Storage::path($credentialsPath)');

    $employeeInfo = new Part([
        'text' => json_encode([
            "Employee Information" => [
                "Employee Name" => "Cristian M. Manalang",
                "Department/Section" => "Corporate",
                "Position Title" => "Accounting Staff",
                "Branch" => "Main Branch",
                "Evaluator" => "Biella Mae R. Mariscotes",
                "Date Hired" => "November 11, 2023"
            ],
        ])
    ]);


    $instruction = new Part([
        'text' => json_encode([
            "Instruction" => "Please conduct evaluations at the third and fifth month of the probationary period, and a final evaluation before the end of the sixth month. For each evaluation, indicate the employee's performance by writing a number between 1 and 4 on the blank line to the right of each performance category, in the appropriate column. Use the following scale: 1 = Needs Improvement, 2 = Meets Expectations, 3 = Exceeds Expectations, 4 = Outstanding."
        ])
    ]);

    $performanceCategories = new Part([
        'text' => json_encode([
            "Performance Categories" => [
                [
                    "Category" => "1. Quantity of Work",
                    "Description" => "Consistently delivers high-quality work, meeting deadlines and completing tasks efficiently with minimal supervision.",
                    "Ratings" => [
                        "3 Months" => 2,
                        "5 Months" => 3,
                        "Final" => 1
                    ]
                ],
                [
                    "Category" => "2. Quality of Work",
                    "Description" => "Produces well-executed, thorough, and accurate work, ensuring effectiveness.",
                    "Ratings" => [
                        "3 Months" => 2,
                        "5 Months" => 3,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "3. Knowledge of Job",
                    "Description" => "Demonstrates a strong understanding of all phases of assigned work, applying knowledge and skills effectively given their current position and experience level.",
                    "Ratings" => [
                        "3 Months" => 4,
                        "5 Months" => 4,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "4. Reliability and Dependability",
                    "Description" => "Consistently delivers high-quality work, demonstrating excellent time management and organizational skills to meet deadlines and responsibilities effectively.",
                    "Ratings" => [
                        "3 Months" => 1,
                        "5 Months" => 2,
                        "Final" => 3
                    ]
                ],
                [
                    "Category" => "5. Communication Skills",
                    "Description" => "Communicates clearly and effectively in both written and oral formats, demonstrating strong organization and attention to detail. Actively listens and demonstrates a comprehension of information received.",
                    "Ratings" => [
                        "3 Months" => 3,
                        "5 Months" => 2,
                        "Final" => 1
                    ]
                ],
                [
                    "Category" => "6. Leadership",
                    "Description" => "Holds himself accountable for achieving goals and objectives.",
                    "Ratings" => [
                        "3 Months" => 4,
                        "5 Months" => 2,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "7. Relations with Supervisor",
                    "Description" => "Maintains a positive attitude when receiving feedback, readily asking questions and implementing suggested improvements. Collaborates effectively with the supervisor to find solutions and address challenges together.",
                    "Ratings" => [
                        "3 Months" => 3,
                        "5 Months" => 1,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "8. Client/Customer Responsiveness",
                    "Description" => "Responds promptly and professionally to inquiries and concerns from external stakeholders/principal clients.",
                    "Ratings" => [
                        "3 Months" => 2,
                        "5 Months" => 1,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "9. Cooperation with Others",
                    "Description" => "Maintains positive and professional relationships with colleagues across all levels (co-workers, supervisors, subordinates).",
                    "Ratings" => [
                        "3 Months" => 1,
                        "5 Months" => 3,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "10. Attendance and Reliability",
                    "Description" => "Maintains a consistent record of attendance, arriving on time for scheduled shifts, demonstrating dependable work habits.",
                    "Ratings" => [
                        "3 Months" => 2,
                        "5 Months" => 3,
                        "Final" => 3
                    ]
                ],
                [
                    "Category" => "11. Judgment and Decision Making",
                    "Description" => "Exercises sound judgment by weighing risks and benefits, and potential consequences of different options.",
                    "Ratings" => [
                        "3 Months" => 1,
                        "5 Months" => 3,
                        "Final" => 3
                    ]
                ],
                [
                    "Category" => "12. Initiative and Flexibility",
                    "Description" => "Demonstrates initiative by readily taking on additional responsibilities and actively seeking out new challenges that contribute to the team's success.",
                    "Ratings" => [
                        "3 Months" => 2,
                        "5 Months" => 3,
                        "Final" => 4
                    ]
                ],
                [
                    "Category" => "13. Capacity to Develop",
                    "Description" => "Readily accepts new and challenging assignments, demonstrating a willingness to learn and grow professionally.",
                    "Ratings" => [
                        "3 Months" => 1,
                        "5 Months" => 4,
                        "Final" => 4
                    ]
                ]
            ]
        ])

    ]);

    $content = new Content();
    $content->setRole('user');
    $content->setParts([$employeeInfo, $instruction, $performanceCategories]);


    $systemInstruction = new Content();
    $systemInstruction->setRole('system');
    $systemInstruction->setParts([
        new Part([
            'text' => "You are an expert hr performance analyst that can give robust and thorough performance improvement plans based on employee's performance evaluation forms. You answer concisely, directly, and in bulleted format. You answer truthfully and straightforward based on the employee’s performance data and you plan what needs to be done for their personalized performance improvement plan."
        ])
    ]);


    // format projects/{PROJECT_NUMBER}/locations/{LOCATION}/endpoints/{ENDPOINT_ID}
    $request = new GenerateContentRequest();
    $request->setModel('projects/1003638908095/locations/us-central1/endpoints/5309718671902375936');
    $request->setContents([$content]);
    $request->setSystemInstruction($systemInstruction);


    $response = $client->generateContent($request);
    dd($response);
});

Route::get('/gemini', function () {

    // adds logging shomn at the top of the paga
    if (app()->environment('local')) {
        putenv('GOOGLE_SDK_PHP_LOGGING=true');
    }

    if (!Storage::exists('services/credentials/vertex-ai.json')) {
        return 'File not found';
    }

    $credentialsPath = 'services/credentials/vertex-ai.json';

    // default prefix of endpoint will be aiplatform.googleapis.com
    // no custom endpoints prefix = response Error 404 page
    $clientOptions = [
        // results { "message": "This endpoint is a dedicated endpoint via CloudESF and cannot be accessed through the Vertex AI API. Please access the endpoint using its dedicated dns name '{ENDPOINT_ID}.us-central1-{PROJECT_NUMBER}.prediction.vertexai.goog'", "code": 9, "status": "FAILED_PRECONDITION", "details": [] }
        'apiEndpoint' => 'us-central1-aiplatform.googleapis.com',

        // results cURL error 6: Could not resolve host: 6904274414968242176.us-central1-1003638908095.prediction.vertexai.goog (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://{ENDPOINT_ID}.us-central1-{PROJECT_NUMBER}.prediction.vertexai.goog/v1/projects/emplo-ocr/locations/us-central1/endpoints/{ENDPOINT_ID}:generateContent?%24alt=json%3Benum-encoding%3Dint
        // 'apiEndpoint' => '6904274414968242176.us-central1-1003638908095.prediction.vertexai.goog',

        // results cURL error 60: SSL: no alternative certificate subject name matches target host name '{ENDPOINT_ID}.us-central1-aiplatform.googleapis.com' (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://{ENDPOINT_ID}.us-central1-aiplatform.googleapis.com/v1/projects/emplo-ocr/locations/us-central1/endpoints/{ENDPOINT_ID}:generateContent?%24alt=json%3Benum-encoding%3Dint
        // 'apiEndpoint' => '6904274414968242176.us-central1-aiplatform.googleapis.com',

        'credentials' => Storage::path($credentialsPath),
    ];

    $client = new PredictionServiceClient($clientOptions);

    putenv('GOOGLE_APPLICATION_CREDENTIALS=Storage::path($credentialsPath)');

    $content = new Content();
    $content->setRole('user');
    $content->setParts([
        new Part([
            'text' => json_encode([
                "Employee Information" => [
                    "Employee Name" => "Cristian M. Manalang",
                    "Department/Section" => "Corporate",
                    "Position Title" => "Accounting Staff",
                    "Branch" => "Main Branch",
                    "Evaluator" => "Biella Mae R. Mariscotes",
                    "Date Hired" => "November 11, 2023"
                ],
                "Performance Categories" => [
                    "1. Quantity of Work" => [
                        "Description" => "Consistently delivers high-quality work, meeting deadlines and completing tasks efficiently with minimal supervision.",
                        "Ratings" => [
                            "3 Months" => 2,
                            "5 Months" => 3,
                            "Final" => 1
                        ]
                    ],
                    "2. Quality of Work" => [
                        "Description" => "Produces well-executed, thorough, and accurate work, ensuring effectiveness.",
                        "Ratings" => [
                            "3 Months" => 2,
                            "5 Months" => 3,
                            "Final" => 4
                        ]
                    ]
                ]
            ])
        ])
    ]);

    $systemInstruction = new Content();
    $systemInstruction->setRole('system');
    $systemInstruction->setParts([
        new Part([
            'text' => "Please conduct evaluations at the third and fifth month of the probationary period, and a final evaluation before the end of the sixth month. For each evaluation, indicate the employee's performance by writing a number between 1 and 4 on the blank line to the right of each performance category, in the appropriate column. Use the following scale: 1 = Needs Improvement, 2 = Meets Expectations, 3 = Exceeds Expectations, 4 = Outstanding."
        ])
    ]);

    // format projects/{PROJECT_NUMBER}/locations/{LOCATION}/endpoints/{ENDPOINT_ID}
    $request = new GenerateContentRequest();
    $request->setModel('projects/emplo-ocr/locations/us-central1/publishers/google/models/gemini-2.0-flash-exp');
    $request->setContents([$content]);
    $request->setSystemInstruction($systemInstruction);

    $response = $client->generateContent($request);

    dd($response);

    return $response;
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

    // default prefix of endpoint will be aiplatform.googleapis.com
    // no custom endpoints prefix = response Error 404 page
    $clientOptions = [
        // results { "message": "This endpoint is a dedicated endpoint via CloudESF and cannot be accessed through the Vertex AI API. Please access the endpoint using its dedicated dns name '{ENDPOINT_ID}.us-central1-{PROJECT_NUMBER}.prediction.vertexai.goog'", "code": 9, "status": "FAILED_PRECONDITION", "details": [] }
        'apiEndpoint' => 'us-central1-aiplatform.googleapis.com:443',

        // results cURL error 6: Could not resolve host: 6904274414968242176.us-central1-1003638908095.prediction.vertexai.goog (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://{ENDPOINT_ID}.us-central1-{PROJECT_NUMBER}.prediction.vertexai.goog/v1/projects/emplo-ocr/locations/us-central1/endpoints/{ENDPOINT_ID}:generateContent?%24alt=json%3Benum-encoding%3Dint
        // 'apiEndpoint' => '6904274414968242176.us-central1-1003638908095.prediction.vertexai.goog',

        // results cURL error 60: SSL: no alternative certificate subject name matches target host name '{ENDPOINT_ID}.us-central1-aiplatform.googleapis.com' (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://{ENDPOINT_ID}.us-central1-aiplatform.googleapis.com/v1/projects/emplo-ocr/locations/us-central1/endpoints/{ENDPOINT_ID}:generateContent?%24alt=json%3Benum-encoding%3Dint
        // 'apiEndpoint' => '6904274414968242176.us-central1-aiplatform.googleapis.com',

        'credentials' => Storage::path($credentialsPath),
    ];

    $client = new ModelServiceClient($clientOptions);

    putenv('GOOGLE_APPLICATION_CREDENTIALS=Storage::path($credentialsPath)');

    $request = new ListModelsRequest();
    $request->setParent('projects/1003638908095/locations/us-central1');

    $response = $client->listModels($request);


    dd($response);
});

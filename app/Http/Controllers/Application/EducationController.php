<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantEducation;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EducationController extends Controller
{
    /* Show all resource */
    // public function index(): ViewFactory|View
    // {
    //     return view();
    // }


    /* Show form page for creating resource */
    // public function create() : ViewFactory|View
    // {
    //     // return view();
    // }

    /* store a new resource */
    public function store(Request|array $request, bool $isValidated = false)
    {
        Log::info('EducationController@store called', ['request' => $request, 'type' => gettype($request['education'])]);

        $messages = [
            'education.*.required' => 'The education field is required.',
            'education.*.string' => 'The education field must be a string.',
        ];

        try {
            if (! $isValidated) {
                if ($request instanceof Request) {
                    $validated = $request->validate([
                        'applicantId' => 'required|exists:applicants,applicant_id',
                        'education' => 'required|array',
                        'education.*' => 'required|string'
                    ], $messages);
                } else {
                    // Manually validate the array
                    $validated = validator($request, [
                        'applicantId' => 'required|exists:applicants,applicant_id',
                        'education' => 'required|array',
                        'education.*' => 'required|string'
                    ], $messages)->validate();
                }
            } else {
                $validated = $request;
            }

            if (is_array($validated)) {
                $education = $validated['education'] ?? null;

                if ($education !== null) {
                    foreach ($education as $edu) {
                        ApplicantEducation::create([
                            'applicant_id' => $validated['applicantId'],
                            'education' => $edu
                        ]);
                    }
                }
            } else {
                // Handle the case where the request is not an array
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Invalid data
        }
    }

    /* Get single resource */
    // public function show(): ViewFactory|View
    // {
    //     return view();
    // }

    /* Patch or edit */
    public function update()
    {
        //
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}

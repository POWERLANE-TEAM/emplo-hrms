<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\ApplicantExperience;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ExperienceController extends Controller
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

        $messages = [
            'experience.*.required' => 'The experience field is required.',
            'experience.*.string' => 'The experience field must be a string.',
        ];

        try {
            if (! $isValidated) {
                if ($request instanceof Request) {
                    $validated = $request->validate([
                        'applicantId' => 'required|exists:applicants,applicant_id',
                        'experience' => 'required|array',
                        'experience.*' => 'required|string',
                    ], $messages);
                } else {
                    // Manually validate the array
                    $validated = validator($request, [
                        'applicantId' => 'required|exists:applicants,applicant_id',
                        'experience' => 'required|array',
                        'experience.*' => 'required|string',
                    ], $messages)->validate();
                }
            } else {
                $validated = $request;
            }

            if (is_array($validated)) {
                $experience = $validated['experience'] ?? null;

                if ($experience !== null) {
                    foreach ($experience as $exp) {
                        ApplicantExperience::create([
                            'applicant_id' => $validated['applicantId'],
                            'experience_desc' => $exp,
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

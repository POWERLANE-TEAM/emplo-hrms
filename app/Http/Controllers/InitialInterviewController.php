<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Models\Application;
use App\Models\InitialInterview;
use App\Rules\ScheduleDateRule;
use App\Rules\ScheduleTimeRule;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InitialInterviewController extends Controller
{
    protected $minDate;

    protected $maxDate;

    protected $minTime;

    protected $maxTime;

    protected $date = '';

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

        if (! $isValidated) {
            if (! auth()->user()->hasPermissionTo(UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE)) {
                abort(403);
            }

            $this->date = $request->input('examination.date');

            $validated = $request->validate([
                'interview.date' => 'required|' . ScheduleDateRule::get($this->minDate, $this->maxDate),
                'interview.time' => (function () {
                    return [
                        'required_with:date',
                        new ScheduleTimeRule($this->date),
                    ];
                })(),
            ]);
        }

        $applicationId = is_array($request) ? $request['applicationId'] : $request->input('applicationId');

        $application = Application::findOrFail($applicationId);

        if (InitialInterview::where('application_id', $applicationId)->exists()) {
            abort(409, 'Initial interview already scheduled.');
        }

        if (is_array($request)) {
            $interviewStartDate = $request['date'];
            $interviewStartTime = $request['time'];
        } else {
            $interviewStartDate = $validated('interview.date');
            $interviewStartTime = $validated('interview.time');
        }

        InitialInterview::create([
            'application_id' => $application->application_id,
            'init_interview_at' => $interviewStartDate . ' ' . $interviewStartTime,
            'init_interviewer' => auth()->user()->user_id,
            'is_init_interview_passed' => false,
        ]);

        // Insert Interview Notification Event Here

    }

    /* Get single resource */
    // public function show(): ViewFactory|View
    // {
    //     return view();
    // }

    /* Patch or edit */
    public function update($request, bool $isValidated = false)
    {
        $applicationId = is_array($request) ? $request['applicationId'] : $request->input('applicationId');

        $application = Application::findOrFail($applicationId);

        if (is_array($request)) {
            $interviewStartDate = $request['date'];
            $interviewStartTime = $request['time'];
        } else {
            // $interviewStartDate = $validated('interview.date');
            // $interviewStartTime = $validated('interview.time');
        }

        $initalInterview = $application->initialInterview;

        $data = [
            'init_interview_at' => $interviewStartDate . ' ' . $interviewStartTime,
            'init_interviewer' => auth()->user()->user_id,
            'is_init_interview_passed' => $request['isPassed'] ?? false,
        ];

        $filteredData = array_filter($data, function ($value) {
            return !is_null($value);
        });

        $initalInterview->update($filteredData);
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}

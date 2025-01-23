<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Models\Application;
use App\Models\FinalInterview;
use App\Models\FinalInterviewRating;
use App\Models\InitialInterview;
use App\Models\InitialInterviewRating;
use App\Rules\ScheduleDateRule;
use App\Rules\ScheduleTimeRule;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FinalInterviewController extends Controller
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
            if (! auth()->user()->hasPermissionTo(UserPermission::CREATE_APPLICANT_FINAL_INTERVIEW_SCHEDULE)) {
                abort(403);
            }

            $this->date = now();

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

        if (FinalInterview::where('application_id', $applicationId)->exists()) {
            abort(409, 'Final interview already scheduled.');
        }

        if (is_array($request)) {
            $interviewStartDate = $request['date'];
            $interviewStartTime = $request['time'];
        } else {
            $interviewStartDate = $validated('interview.date');
            $interviewStartTime = $validated('interview.time');
        }

        FinalInterview::create([
            'application_id' => $application->application_id,
            'final_interview_at' => $interviewStartDate . ' ' . $interviewStartTime,
            'final_interviewer' => auth()->user()->user_id,
        ]);

        $application->update([
            'application_status_id' => ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value,
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
            $interviewStartDate = $request['date'] ?? null;
            $interviewStartTime = $request['time'] ?? null;

        } else {
            // $interviewStartDate = $validated('interview.date');
            // $interviewStartTime = $validated('interview.time');
        }

        $interviewStart = null;
        if (!is_null($interviewStartDate) && !is_null($interviewStartTime)) {
            $interviewStart = $interviewStartDate . ' ' . $interviewStartTime;
        }

        $interview = $application->finalInterview;
        $interviewRatings = is_array($request) ? $request['interviewRatings'] : $request->input('interviewRatings');

        $data = [
            'final_interview_at' => $interviewStart,
            'final_interviewer' => auth()->user()->user_id,
            'is_final_interview_passed' => $request['isPassed'] ?? false,
        ];

        $filteredData = array_filter($data, function ($value) {
            return !is_null($value);
        });

        if(!empty($interviewRatings)){
            foreach ($interviewRatings as $key => $parameter) {
                $isExist = FinalInterviewRating::parameter($key)->interview($interview)->exists();

                if($isExist){
                    FinalInterviewRating::parameter($key)->interview($interview)->update([
                        'rating_id' => $parameter,
                    ]);
                }else{
                    FinalInterviewRating::create([
                        'interview_id' => $interview->interview_id,
                        'parameter_id' => $key,
                        'rating_id' => $parameter,
                    ]);
                }
            }

        }

        $interview->update($filteredData);
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}

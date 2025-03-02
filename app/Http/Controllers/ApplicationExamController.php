<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Http\Controllers\Application\ApplicationController;
use App\Models\Application;
use App\Models\ApplicationExam;
use App\Notifications\Applicant\ExamScheduled;
use App\Rules\ScheduleDateRule;
use App\Rules\ScheduleTimeRule;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationExamController extends Controller
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

        $validated = null;

        if (! $isValidated) {

            if (! auth()->user()->hasPermissionTo(UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE)) {
                abort(403);
            }

            $this->date = $request->input('examination.date');

            $validated = $request->validate([
                'examination.date' => 'required|' . ScheduleDateRule::get($this->minDate, $this->maxDate),
                'examination.time' => (function () {
                    return [
                        'required_with:date',
                        new ScheduleTimeRule($this->date),
                    ];
                })(),
            ]);
        }

        DB::transaction(function () use ($request, $validated) {
            $applicationId = is_array($request) ? $request['applicationId'] : $request->input('applicationId');

            $application = Application::with(['applicant.account'])->findOrFail($applicationId);

            if (ApplicationExam::where('application_id', $applicationId)->exists()) {
                abort(409, 'Application already has an exam scheduled.');
            }

            if (is_array($request)) {
                $examStartDate = $request['date'];
                $examStartTime = $request['time'];
            } else {
                $examStartDate = $validated('examination.date');
                $examStartTime = $validated('examination.time');
            }

            $examStart = $examStartDate . ' ' . $examStartTime;

            $examDuration = '00:30:00';
            [$hours, $minutes, $seconds] = explode(':', $examDuration);
            $totalMinutes = ($hours * 60) + $minutes + ($seconds / 60);
            $examEnd = date('Y-m-d H:i:s', strtotime($examStart . ' + ' . $hours . ' hours ' . $minutes . ' minutes ' . $seconds . ' seconds'));

            ApplicationExam::create([
                'application_id' => $applicationId,
                'start_time' => $examStart,
                'end_time' => $examEnd,
            ]);

            $applicationController = new ApplicationController;

            $applicationUpdate = [
                'applicationId' => $applicationId,
                'applicationStatusId' => ApplicationStatus::ASSESSMENT_SCHEDULED->value,
            ];

            $applicationController->update($applicationUpdate, true);

            $user = $application->applicant->account;

            $notification = new ExamScheduled(
                ['email' => env('MAIL_FROM_ADDRESS')],
                $examStart,
                $examEnd
            );
            $user->notify($notification);
        });
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
        $examResult = is_array($request) ? ($request['examResult'] ?? null) : $request->input('examResult', null);

        $application = Application::with(['applicant.account'])->findOrFail($applicationId);

        if (is_array($request)) {
            $examStartDate = $request['date'] ?? null;
            $examStartTime = $request['time'] ?? null;
        } else {

        }

        $examStart = null;
        if (!is_null($examStartDate) && !is_null($examStartTime)) {
            $examStart = $examStartDate . ' ' . $examStartTime;
        }

        $exam = ApplicationExam::where('application_id', $application->application_id)->first();

        $updateData = [];

        if (!is_null($examStart)) {
            $updateData['start_time'] = $examStart;
        }

        if (!is_null($examResult)) {
            $updateData['passed'] = $examResult;
            $updateData['assigned_by'] = auth()->user()->account_id;
        }

        if (!empty($updateData)) {
            $exam->update($updateData);
        }
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}

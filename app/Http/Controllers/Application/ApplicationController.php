<?php

namespace App\Http\Controllers\Application;

use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /* Show all resource */
    public function index($applicationStatus)
    {
        return view('employee.application.index', ['applicationStatus' => $applicationStatus]);
    }

    /* Show form page for creating resource */
    public function create(Request|array $request, bool $isValidated = false)
    {
        $jobVacancyId = is_array($request) ? $request['jobVacancyId'] : $request->input('jobVacancyId');
        $applicantId = is_array($request) ? $request['applicantId'] : $request->input('applicantId');

        if (! $isValidated) {
            // $validated = $request->validate([
            // //
            // ]);

            $jobVacancy = JobVacancy::findOrFail($jobVacancyId);
            $applicant = JobVacancy::findOrFail($applicantId);
        }

        $application = Application::create([
            'job_vacancy_id' => $jobVacancyId,
            'applicant_id' => $applicantId ?? auth()->user()->account->applicant_id,
            'application_status_id' => ApplicationStatus::PENDING,
        ]);

        return $application;
    }

    /* store a new resource */
    public function store()
    {
        //
    }

    /* Get single resource */
    public function show(Application $application)
    {
        $application->load('applicant.account', 'vacancy.jobTitle');

        $routeApplicationCategory = [
            ApplicationStatus::PENDING->value => 'pending',
            ApplicationStatus::ASSESSMENT_SCHEDULED->value => 'qualified',
            ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value => 'qualified',
            ApplicationStatus::PRE_EMPLOYED->value => 'preemployed',
            ApplicationStatus::REJECTED->value => 'rejected',
        ];


        $status = $routeApplicationCategory[$application->application_status_id];

        return view(
            'employee.application.show',
            [
                'application' => $application,
                'status' => in_array($status, ['pending', 'qualified', 'preemployed']) ? $status : 'pending'
            ]
        );
    }

    /* Patch or edit */
    public function update(Request|array $request, bool $isValidated = false)
    {
        try {
            $applicationId = is_array($request) ? $request['applicationId'] : $request->input('application.applicationId');

            $application = Application::findOrFail($applicationId);

            $user = auth()->user();

            $user->loadMissing('account.application');



            // check if the user updating the application is the applicant himself
            $isTheApplicant = false;
            if ($user && $user->account && $user->account->application) {
                $isTheApplicant = $user->account->application->application_id == $applicationId;
            }

            if (! $isValidated) {

                $validated = $request->validate([
                    'jobVacancyId' => 'nullable|integer|exists:job_vacancies,job_vacancy_id',
                    'applicationStatusId' => 'nullable|integer|in:' . implode(',', ApplicationStatus::values()),
                    'hireDate' => [
                        'nullable',

                        'date',
                        Rule::in([Carbon::today()->toDateString()]),
                    ],
                ]);
            }

            // extract value either from form or livewire(array)
            if (is_array($request)) {
                $jobVacancyId = $request['jobVacancyId'] ?? null;
                $applicationStatusId = $request['applicationStatusId'] ?? null;
                $hireDate = $request['hireDate'] ?? null;
                $isPassed = $request['isPassed'] ?? null;
            } else {
                $jobVacancyId = $validated('jobVacancyId') ?? null;
                $applicationStatusId = $validated('applicationStatusId') ?? null;
                $hireDate = $validated('hireDate') ?? null;
                $isPassed = $validated('isPassed') ?? null;
            }

            // Check permission to modify
            if (! $isTheApplicant) {

                // Check if already hired but allow admin to update hired application
                if ($application->hired_at != null && ! (auth()->user()->hasRole(UserRole::ADVANCED))) {
                    abort(403, 'Application status can no longer be updated.');
                }

                // check if user has permission to update pending application
                if ($application->application_status_id == ApplicationStatus::PENDING && ! (auth()->user()->hasPermission(UserPermission::UPDATE_PENDING_APPLICATION_STATUS))) {
                    abort(403, 'Pending');
                }

                // check if user has permission to update application that has exam and initial interview scheduled
                if ($application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED && ! (auth()->user()->hasPermission(UserPermission::UPDATE_QUALIFIED_APPLICATION_STATUS))) {
                    abort(403, 'Assessment scheduled');
                }

                // check if user has permission to update pre employed application to an employee
                if ($application->application_status_id == ApplicationStatus::PRE_EMPLOYED && ! (auth()->user()->hasPermission(UserPermission::UPDATE_PRE_EMPLOYED_APPLICATION_STATUS))) {
                    abort(403, 'Pre employed');
                }
            } elseif (! is_null($applicationStatusId) || ! is_null($hireDate) || ! is_null($isPassed)) {
                // if applicant tries to update application status
                abort(403, 'Application status cannot be updated by applicant.');
            }

            // If application is pending review limit the update to only allowed status
            if ($application->application_status_id == ApplicationStatus::PENDING && ! in_array($applicationStatusId, ApplicationStatus::allowedPendingStatusUpdates())) {
                abort(403, 'Invalid application update process.');
            }

            if ($application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED && ! in_array($applicationStatusId, ApplicationStatus::allowedAssessedStatusUpdates())) {
                abort(403, 'Invalid application update process.');
            }

            if ($application->application_status_id == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED && ! in_array($applicationStatusId, ApplicationStatus::allowedFinalInterviewStatusUpdates())) {
                abort(403, 'Invalid application update process.');
            }

            // if ($application->application_status_id == ApplicationStatus::REJECTED) {
            //     //   maybe  make a diff message of why rejected application is reconsidered
            // }

            // update date that is present in the request
            $updateData = array_filter([
                'job_vacancy_id' => $jobVacancyId,
                'application_status_id' => $applicationStatusId,
            ], function ($value) {
                return ! is_null($value);
            });

            // If allow nullable update to hired_at
            $updateData['hired_at'] = $hireDate;

            $application->update($updateData);
        } catch (\Throwable $th) {
            if (app()->environment('local')) {
                throw ($th);
            } else {
                report($th);
            }
        }
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}

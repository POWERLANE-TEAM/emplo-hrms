<?php

namespace App\Http\Controllers\Application;

use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;



class ApplicationController extends Controller
{
    /* Show all resource */
    public function index($page = null)
    {
        if (empty($page) || $page == 'index') {
            return view('employee.application.index');
        }
    }


    /* Show form page for creating resource */
    public function create()
    {
        //
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
        return view('employee.application.show', ['application' => $application]);
    }

    /* Patch or edit */
    public function update(Request|array $request, bool $isValidated = false)
    {
        $applicationId = is_array($request) ? $request['applicationId'] : $request->input('application.applicationId');

        $application = Application::findOrFail($applicationId);

        $user = auth()->user()->load('account.application');

        if ($user && $user->account && $user->account->application) {
            $isTheApplicant = $user->account->application->application_id == $applicationId;
        }


        // Check permission to modify
        if (!$isTheApplicant && !auth()->user()->hasPermissionTo(UserPermission::UPDATE_APPLICATION_STATUS)) {
            abort(403);
        }

        // Check if already hired but allow admin to update hired application
        if ($application->hired_at != null && !(auth()->user()->hasRole(UserRole::ADVANCED))) {
            abort(403);
        }

        if (!$isValidated) {

            $validated = $request->validate([
                'jobVacancyId' => 'bail|nullable|integer|exists:job_vacancies,job_vacancy_id',
                'applicationStatusId' => 'bail|nullable|integer|in:' . implode(',', ApplicationStatus::values()),
                'hireDate' => [
                    'nullable',
                    'bail',
                    'date',
                    Rule::in([Carbon::today()->toDateString()]),
                ],
            ]);
        }

        // extract value either from form or livewire(array)
        if (is_array($request)) {
            $jobVacancyId = $request['jobVacancyId'] ?? null;
            $applicationStatusId = $request['applicationStatusId'];
            $hireDate = $request['hireDate'] ?? null;
        } else {
            $jobVacancyId = $validated('jobVacancyId') ?? null;
            $applicationStatusId = $validated('applicationStatusId');
            $hireDate = $validated('hireDate') ?? null;
        }

        // If application is pending review limit the update to only allowed status
        if ($application->application_status_id == ApplicationStatus::PENDING && !in_array($applicationStatusId, ApplicationStatus::allowedPendingStatusUpdates())) {
            abort(403, 'Invalid application update process.');
        }

        // update date that is present in the request
        $updateData = array_filter([
            'job_vacancy_id' => $jobVacancyId,
            'application_status_id' => $applicationStatusId,
        ], function ($value) {
            return !is_null($value);
        });

        // If allow nullable update to hired_at
        $updateData['hired_at'] = $hireDate;

        $application->update($updateData);
    }

    /* Delete */
    public function destroy()
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Application;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\AccountType;
use App\Enums\UserPermission;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\JobVacancy;
use App\Models\User;
use App\Traits\Applicant as ApplicantTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;

class ApplicantController extends Controller
{
    use ApplicantTrait;

    /* Show all resource */
    public function index($page = null)
    {
        if (empty($page) || $page == 'index') {
            return view('applicant/index');
        }
    }

    /* Show form page for creating resource */
    public function create()
    {
        // add check if authenticated is guest or applicant
        if ($this->canApply()) {
            return view('apply');
        }
    }

    /* store a new resource */
    public function store(Request|array $request, bool $isValidated = false)
    {
        if ($this->canApply(true));

        $jobVacancyId = is_array($request) ? $request['application']['jobVacancyId'] : $request->input('jobVacancyId');

        $jobVacancy = JobVacancy::findOrFail($jobVacancyId);

        $user = auth()->user();

        Log::info('ApplicantController@store', ['user' => $user]);

        if (! $isValidated) {
            // $validated = $request->validate([
            // ]);
        }

        if (is_array($request)) {
            $firstName = $request['firstName'];
            $middleName = $request['middleName'] ?? null;
            $presentAddress = $request['lastName'];
            $presentBarangay = $request['presentBarangay'];
            $permanentAddress = $request['permanentAddress'];
            $permanentBarangay = $request['permanentBarangay'];

            try {
                $phone = new PhoneNumber($request['contactNumber'], 'PH');
                $contactNumber = $phone->formatNational();
            } catch (\Exception $e) {
                // Handle invalid phone number
                $contactNumber = 'Not set';
            }

            $sex = $request['sex'];
            $civilStatus = $request['civilStatus'];
            $dateOfBirth = $request['dateOfBirth'] ?? null;
            $education = is_string($request['education']) ? json_decode($request['education'], true) : $request['education'];
            $experience = is_string($request['experience']) ? json_decode($request['experience'], true) : $request['experience'];
        } else {
            // $jobVacancyId = $validated('jobVacancyId') ?? null;
        }



        $newApplicant = Applicant::create([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $presentAddress,
            'present_address' => $presentAddress,
            'present_barangay' => $presentBarangay,
            'permanent_address' => $permanentAddress,
            'permanent_barangay' => $permanentBarangay,
            'contact_number' => $contactNumber,
            'sex' => $sex,
            'civil_status' => $civilStatus,
            'date_of_birth' => $dateOfBirth,
            'education' => $education,
            'experience' => $experience,
        ]);

        if (is_array($request)) {
            $request['application']['applicantId'] = $newApplicant->applicant_id;
            $request['user']['accountId'] = $newApplicant->applicant_id;
            $request['user']['accountType'] = AccountType::APPLICANT->value;
            $request['user']['userStatusId'] = UserStatus::ACTIVE->value;

            $application =  $request['application'];
            $applicantUser =  $request['user'];
        } else {
            //
        }

        $applicationController  = new ApplicationController();

        $userToApplicantController  = new UpdateUserProfileInformation();

        $errors = session()->get('errors');
        $userToApplicantController->update($user, $applicantUser);

        // add save resume file

        $applicationController->create($application, true);
    }

    /* Get single resource */
    public function show()
    {
        //
    }

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

    private function canApply(?bool $isTerminate = false)
    {
        return !self::applicantOrYet(!Auth::user()->hasPermissionTo(UserPermission::VIEW_JOB_APPLICATION_FORM->value), $isTerminate) || !self::hasApplication($isTerminate);
    }
}

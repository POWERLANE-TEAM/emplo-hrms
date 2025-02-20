<?php

namespace App\Http\Controllers\Application;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\AccountType;
use App\Enums\UserPermission;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Helpers\RouteHelper;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\ApplicationDoc;
use App\Models\JobVacancy;
use App\Models\User;
use App\Notifications\Applicant\AccountCreated;
use App\Traits\Applicant as ApplicantTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\FilePath;
use Propaganistas\LaravelPhone\PhoneNumber;

class ApplicantController extends Controller
{
    use ApplicantTrait;

    /* Show all resource */
    public function index()
    {
        //
    }

    /* Show form page for creating resource */
    public function create($job)
    {

        $job = RouteHelper::validateModel(JobVacancy::class, $job);

        $job = $job->load('jobTitle');

        // add check if authenticated is guest or applicant
        if ($this->canApply()) {
            return view('apply', ['job' => $job]);
        }
    }

    /* store a new resource */
    public function store(Request|array $request, bool $isValidated = false)
    {

        if ($this->canApply(true));

        $jobVacancyId = is_array($request) ? $request['application']['jobVacancyId'] : $request->input('jobVacancyId');

        $jobVacancy = JobVacancy::findOrFail($jobVacancyId);

        $user = auth()->user();

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
        } else {
            // $jobVacancyId = $validated('jobVacancyId') ?? null;
        }

        DB::beginTransaction();

        try {
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

            $educationController = new EducationController();
            $educationController->store([
                'applicantId' => $newApplicant->applicant_id,
                'education' => $request['education']
            ], false);

            $experienceController = new ExperienceController();
            $experienceController->store([
                'applicantId' => $newApplicant->applicant_id,
                'experience' => $request['experience']
            ], false);

            $skillController = new SkillController();
            $skillController->store([
                'applicantId' => $newApplicant->applicant_id,
                'skills' => $request['skills']
            ], false);

            $userToApplicantController  = new UpdateUserProfileInformation();

            $errors = session()->get('errors');
            $userToApplicantController->update($user, $applicantUser);

            // add save resume file

            $application = $applicationController->create($application, true);

            $resumeFile = is_array($request) ? $request['resumeFile'] : $request->input('resumeFile');

            $hashedResume = $resumeFile->hashName();

            $path = $resumeFile->storeAs(FilePath::RESUME->value, $hashedResume, 'public');

            ApplicationDoc::create([
                'application_id' => $application->application_id,
                'file_path' => $path,
                'preemp_req_id' => 17, //resume
            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }

        try {
            $user->notify(new AccountCreated($user->user_id, $application->application_id));
        } catch (\Throwable $th) {
            report($th);
        }

        try {
            $permissions = [
                UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value,
                UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value,
                UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value,
            ];

            $usersWithPermissions = User::permission($permissions)->select('user_id')->get();

            foreach ($usersWithPermissions as $otherUser) {
                $otherUser->notify(new AccountCreated($user->user_id, $application->application_id, ['database', 'broadcast']));
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }

    /* Get single resource */
    public function show($application = null)
    {
        if (empty($application) && auth()->check()) {
            // dump(auth()->user());
            $application = auth()->user()->account->application;
        } else if (auth()->check()) {
            $application = RouteHelper::validateModel(Application::class, $application);
        }

        $application = $application->load('applicant', 'vacancy.jobTitle', 'status');
        // dd($application);
        return view('applicant/index', ['application' => $application]);
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

    private function canApply(?bool $isTerminate = true)
    {
        return !self::applicantOrYet(!Auth::user()->hasPermissionTo(UserPermission::VIEW_JOB_APPLICATION_FORM->value), $isTerminate) && !self::hasApplication($isTerminate);
    }
}

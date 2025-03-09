<?php

namespace App\Livewire\Forms;

use App\Enums\AccountType;
use App\Enums\ContractType;
use App\Enums\FilePath;
use App\Enums\UserStatus;
use App\Models\Barangay;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\Shift;
use App\Models\SpecificArea;
use App\Models\User;
use App\Notifications\EmployeeAccountCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Permission\Models\Role;

class CreateAccountForm extends Form
{
    use WithFileUploads;

    /** @var string $firstName */
    #[Validate('required')]
    public $firstName;

    /** @var string|null $middleName */
    #[Validate('nullable')]
    public $middleName;

    /** @var string $lastName */
    #[Validate('required')]
    public $lastName;

    /** @var string $email */
    #[Validate('required|email:rfc,dns,spoof|max:320|unique:users,email')]
    public $email;

    /** @var string $contactNumber */
    #[Validate('required|numeric|digits:11')]
    public $contactNumber;

    /** @var string $presentRegion */
    #[Validate('required')]
    public $presentRegion;

    /**
     * Some regions don't have provinces, like NCR.
     *
     * @var string|null $presentProvince
     */
    #[Validate('nullable')]
    public $presentProvince;

    /** @var string $presentCity */
    #[Validate('required')]
    public $presentCity;

    /**
     * Hold the primary key(id) of the barangay.
     *
     * @var int $presentBarangay
     */
    #[Validate('required')]
    public $presentBarangay;

    /** @var string $presentAddress */
    #[Validate('required')]
    public $presentAddress;

    /** @var string $permanentRegion */
    #[Validate('required')]
    public $permanentRegion;

    /**
     * Some regions don't have provinces, like NCR.
     *
     * @var string|null $permanentProvince
     */
    #[Validate('nullable')]
    public $permanentProvince;

    /** @var string $permanentCity */
    #[Validate('required')]
    public $permanentCity;

    /**
     * Hold the primary key(id) of the barangay.
     *
     * @var int $permanentBarangay
     */
    #[Validate('required')]
    public $permanentBarangay;

    /** @var string $permanentAddress */
    #[Validate('required')]
    public $permanentAddress;

    /** @var string $birthDate */
    #[Validate('required')]
    public $birthDate;

    /** @var string $sex */
    #[Validate('required')]
    public $sex;

    /** @var string $civilStatus */
    #[Validate('required')]
    public $civilStatus;

    /**
     * Hold the primary key(job_family_id) of the job family.
     *
     * @var int $jobFamily
     */
    #[Validate('required')]
    public $jobFamily;

    /**
     * Hold the primary key(job_title_id) of the job title.
     *
     * @var int $jobTitle
     */
    #[Validate('required')]
    public $jobTitle;

    /**
     * Hold the primary key(job_level_id) of the job level.
     *
     * @var int $jobLevel
     */
    #[Validate('required')]
    public $jobLevel;

    /**
     * Hold the primary key(area_id) of the specific area/branch.
     *
     * @var int $area
     */
    #[Validate('required')]
    public $area;

    /** @var string $role */
    #[Validate('required')]
    public $role;

    /**
     * Hold the primary key(emp_status_id) of the employment status.
     *
     * @var int $employmentStatus
     */
    #[Validate('required')]
    public $employmentStatus;

    /**
     * Hold the primary key(shift_id) of the shift/schedule
     *
     * @var int $shift
     */
    #[Validate('required')]
    public $shift;

    /** @var string $sss */
    #[Validate('required|digits:10|numeric')]
    public $sss;

    /** @var string $philhealth */
    #[Validate('required|digits:12|numeric')]
    public $philhealth;

    /** @var string $tin */
    #[Validate('required|digits:12|numeric')]
    public $tin;

    /** @var string $pagibig */
    #[Validate('required|digits:12|numeric')]
    public $pagibig;

    /** @var string */
    private $password;

    /**
     * For storing instance of a new User model.
     */
    private User $newUser;

    #[Validate('max:10240', message: '10 mb maximum per upload only.')]
    #[Validate('mimes:pdf', message: 'Only pdf is allowed.')]
    public $contract;

    public $hiredAt;

    public $startedAt;

    public $vacationLeaveCredits;

    public $sickLeaveCredits;

    /**
     * Begin db transaction and perform insertions and stuff.
     *
     * @return void
     */
    public function create()
    {
        DB::transaction(function () {
            LogBatch::startBatch();

            $employee = $this->storeEmployee();

            $this->storeJobDetails($employee);

            $this->storeContract($employee);

            $this->storeStartingDate($employee);

            $this->storeSilCredits($employee);

            $newUser = $this->storeUser($employee);

            $this->assignRole($newUser);

            $this->newUser = $newUser;

            LogBatch::endBatch();
        });

        $this->newUser->notify(new EmployeeAccountCreated($this->newUser, $this->password));
    }

    /**
     * Store and create new user account.
     *
     * @return \App\Models\User
     */
    private function storeUser(Employee $employee)
    {
        $this->password = Str::random();

        return $employee->account()->create([
            'account_type' => AccountType::EMPLOYEE,
            'account_id' => $employee->employee_id,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'user_status_id' => UserStatus::NOT_VERIFIED,
        ]);
    }

    private function storeContract(Employee $employee)
    {
        Storage::disk('local')->makeDirectory(FilePath::CONTRACTS->value);

        $hashedVersion = sprintf(
            '%s-%s', $this->contract->hashName(), $employee->employee_id
        );

        $this->contract->storeAs(FilePath::CONTRACTS->value, $hashedVersion, 'local');

        return $employee->contracts()->create([
            'type' => ContractType::CONTRACT,
            'uploaded_by' => Auth::user()->account->employee_id,
            'hashed_attachment' => $hashedVersion,
            'attachment_name' => $this->contract->getClientOriginalName(),
        ]);
    }

    private function storeStartingDate(Employee $employee)
    {
        return $employee->lifecycle()->create([
            'started_at' => $this->startedAt,
        ]);
    }

    private function storeSilCredits(Employee $employee)
    {
        return $employee->silCredit()->create([
            'vacation_leave_credits' => $this->vacationLeaveCredits,
            'sick_leave_credits' => $this->sickLeaveCredits,
        ]);
    }

    /**
     * Assign role to the newly created user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    private function assignRole(User $newUser)
    {
        Role::where('name', $this->role);
        $newUser->assignRole($this->role);
    }

    /**
     * Store employee information and job detail id.
     *
     * @return \App\Models\Employee
     */
    private function storeEmployee()
    {
        return Employee::create([
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,

            'present_address' => $this->presentAddress,
            'present_barangay' => Barangay::findOrFail($this->presentBarangay)->id,
            'permanent_address' => $this->permanentAddress,
            'permanent_barangay' => Barangay::findOrFail($this->permanentBarangay)->id,
            'contact_number' => $this->contactNumber,
            'sex' => $this->sex,
            'civil_status' => $this->civilStatus,
            'date_of_birth' => $this->birthDate,
            'sss_no' => $this->sss,
            'philhealth_no' => $this->philhealth,
            'tin_no' => $this->tin,
            'pag_ibig_no' => $this->pagibig,
        ]);
    }

    /**
     * Map and store IDs for each foreign columns of job titles, levels, families, and areas/branches.
     *
     * @return \App\Models\JobDetail
     */
    private function storeJobDetails(Employee $employee)
    {
        return $employee->jobDetail()->create([
            'job_title_id' => JobTitle::findOrFail($this->jobTitle)->job_title_id,
            'job_level_id' => JobLevel::findOrFail($this->jobLevel)->job_level_id,
            'job_family_id' => JobFamily::findOrFail($this->jobFamily)->job_family_id,
            'area_id' => SpecificArea::findOrFail($this->area)->area_id,
            'shift_id' => Shift::findOrFail($this->shift)->shift_id,
            'emp_status_id' => EmploymentStatus::findOrFail($this->employmentStatus)->emp_status_id,
            'hired_at' => $this->hiredAt,
        ]);
    }
}

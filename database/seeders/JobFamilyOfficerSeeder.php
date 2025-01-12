<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Enums\UserStatus;
use App\Models\JobFamily;
use App\Enums\AccountType;
use Illuminate\Support\Arr;
use App\Models\SpecificArea;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\RolesAndPermissionsSeeder;

class JobFamilyOfficerSeeder extends Seeder
{
    protected static $freeEmailDomain = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'supervisor' => [
                'Accounting' => ['Acctg. Asst. Supervisor', 'acctg.supervisor'],
                'Administrative' => ['GA Asst. Manager', 'admin.supervisor'],
                'Human Resources-Operations' => ['HR & Admin Supervisor', 'hr-ops.supervisor'],
                'General Affairs' => ['GA Asst. Manager', 'ga.supervisor'],
                'Payroll' => ['Payroll Asst. Supervisor', 'payroll.supervisor'],
                'Operations' => ['GA Asst. Manager', 'ops.supervisor'],
                'General Affairs-Support' => ['GA Asst. Manager', 'ga-support.supervisor'],
            ],
            'office_head' => [
                'Accounting' => ['Acctg. Asst. Manager', 'acctg.manager'],
                'Administrative' => ['Admin Manager', 'admin.manager'],
                'Human Resources-Operations' => ['HRD Manager', 'hrd.manager'],
                'General Affairs' => ['GA Asst. Manager', 'ga.manager'],
                'Payroll' => ['Payroll Asst. Manager', 'payroll.manager'],
                'Operations' => ['GA Asst. Manager', 'ops.manager'],
                'General Affairs-Support' => ['GA Asst. Manager', 'ga-support.manager'],
            ],
        ];

        activity()->withoutLogs(function () use ($roles) {
            $families = JobFamily::all()->map(function ($jobFamily) use ($roles) {
                $familyName = $jobFamily->job_family_name;

                $supervisorInfo = $roles['supervisor'][$familyName];
                $officeHeadInfo = $roles['office_head'][$familyName];

                $supervisor = $this->createEmployee($supervisorInfo);
                $officeHead = $this->createEmployee($officeHeadInfo);

                return [
                    'job_family_id' => $jobFamily->job_family_id,
                    'supervisor' => $supervisor->employee_id,
                    'office_head' => $officeHead->employee_id,
                ];  
            })->toArray();

            // fuck yyou!!
            DB::table('job_families')->where(function () use ($families) {
                foreach ($families as $family) {
                    DB::table('job_families')
                        ->where('job_family_id', $family['job_family_id'])
                        ->update([
                            'supervisor' => $family['supervisor'],
                            'office_head' => $family['office_head'],
                        ]);
                }
            });
        });
    }

    /**
     * Create a new employee for the given role information.
     */
    public static function createEmployee(array $roleInfo): Employee
    {
        [$jobTitle, $emailPrefix] = $roleInfo;

        $employee = Employee::factory()->create();

        $employee->jobDetail()->create([
            'job_title_id' => JobTitle::whereLike('job_title', "%{$jobTitle}%")->first()->job_title_id,
            'area_id'       => SpecificArea::where('area_name', 'Head Office')->first()->area_id,
            'shift_id'      => Shift::inRandomOrder()->first()->shift_id,
            'emp_status_id' => EmploymentStatus::REGULAR,
        ]);

        $file = File::json(base_path('resources/js/email-domain-list.json'));
        self::$freeEmailDomain = $file['valid_email'];
        $validDomains = Arr::random(self::$freeEmailDomain);

        $email = "{$emailPrefix}." . fake()->unique()->userName() . "@{$validDomains}";

        $newUser = User::factory()->create([
            'account_type'      => AccountType::EMPLOYEE,
            'account_id'        => $employee->employee_id,
            'email'             => $email,
            'password'          => Hash::make('UniqP@ssw0rd'),
            'user_status_id'    => UserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ]);

        $newUser->assignRole(UserRole::BASIC);
        $newUser->givePermissionTo(RolesAndPermissionsSeeder::managerialPermissions());

        return $employee;
    }
}


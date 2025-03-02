<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\EmploymentStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Employee;
use App\Models\JobFamily;
use App\Models\Shift;
use App\Models\SpecificArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProbationaryEmployeesSeeder extends Seeder
{
    protected static $freeEmailDomain = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->disableLogging();

        $jobFamilies = JobFamily::with('jobTitles')->get();

        $jobFamilies->each(function ($family) {
            $employee = Employee::factory()->create();
            $family = $family->jobTitles->first();
            $name = $family->job_title;

            $employee->jobDetail()->create([
                'job_title_id' => $family->job_title_id,
                'area_id' => SpecificArea::where('area_name', 'Head Office')->first()->area_id,
                'shift_id' => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => EmploymentStatus::PROBATIONARY,
            ]);

            $this->createUser($employee, $name);
        });

        activity()->enableLogging();
    }

    private function createUser(Employee $employee, string $jobFamily)
    {
        $file = File::json(base_path('resources/js/email-domain-list.json'));
        self::$freeEmailDomain = $file['valid_email'];
        $validDomains = Arr::random(self::$freeEmailDomain);

        $filteredEmail = strtolower($jobFamily).fake()->userName()."@{$validDomains}";

        $trimmedEmail = preg_replace('/[\/\s]+/', '.', $filteredEmail);

        $newUser = $employee->account()->create([
            'account_type' => AccountType::EMPLOYEE,
            'account_id' => $employee->employee_id,
            'email' => $trimmedEmail,
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => UserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ]);

        $newUser->assignRole(UserRole::BASIC);
    }
}

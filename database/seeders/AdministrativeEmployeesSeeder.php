<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Enums\UserStatus;
use App\Models\JobFamily;
use App\Enums\AccountType;
use Illuminate\Support\Arr;
use App\Models\SpecificArea;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdministrativeEmployeesSeeder extends Seeder
{
    protected static $freeEmailDomain = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $employee = Employee::factory()->create();

            $jobTitle = JobFamily::with(['jobTitles' => function ($query) {
                $query->whereLike('job_title', "%Office Staff%");
            }])
                ->whereLike('job_family_name', "%Administrative%")
                ->first()->jobTitles->random()->job_title_id;

            $employee->jobDetail()->updateOrCreate([
                'job_title_id'  => $jobTitle,
                'area_id'       => SpecificArea::where('area_name', 'Head Office')->first()->area_id,
                'shift_id'      => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => EmploymentStatus::REGULAR,
            ]);

            $file = File::json(base_path('resources/js/email-domain-list.json'));
            self::$freeEmailDomain = $file['valid_email'];
            $validDomains = Arr::random(self::$freeEmailDomain);
    
            $userData = [
                'account_type'      => AccountType::EMPLOYEE,
                'account_id'        => $employee->employee_id,
                'email'             => "administrative_employee@{$validDomains}",
                'password'          => Hash::make('UniqP@ssw0rd'),
                'user_status_id'    => UserStatus::ACTIVE,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];
    
            $employeeUser = User::factory()->create($userData);
    
            $employeeUser->assignRole(UserRole::BASIC);
        });
    }
}

<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\EmploymentStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\Shift;
use App\Models\SpecificArea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class SeparatedEmployeeSeeder extends Seeder
{
    protected static $freeEmailDomain = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->disableLogging();

        $employees = Employee::factory(10)->create();

        $employees->each(function ($employee) {
            $employee->jobDetail()->updateOrCreate([
                'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
                'area_id' => SpecificArea::where('area_name', 'Head Office')->first()->area_id,
                'shift_id' => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => fake()->randomElement([
                    EmploymentStatus::RESIGNED,
                    EmploymentStatus::RETIRED,
                    EmploymentStatus::TERMINATED,
                ]),
            ]);

            $employee->lifecycle()->create([
                'started_at' => now()->subYears(5)->toDateTimeString(),
                'separated_at' => fake()->randomElement([
                    now()->subYears(3)->toDateTimeString(),
                    now()->subYears(2)->toDateTimeString(),
                    now()->subYears(1)->toDateTimeString(),
                ]),
                'created_at' => now()->subYears(5)->toDateTimeString(),
                'updated_at' => now()->subYears(4)->toDateTimeString(),
            ]);

            $file = File::json(base_path('resources/js/email-domain-list.json'));
            self::$freeEmailDomain = $file['valid_email'];
            $validDomains = Arr::random(self::$freeEmailDomain);

            $userData = [
                'account_type' => AccountType::EMPLOYEE,
                'account_id' => $employee->employee_id,
                'email' => 'separated.employee.'.fake()->userName()."@{$validDomains}",
                'password' => Hash::make('UniqP@ssw0rd'),
                'user_status_id' => UserStatus::ACTIVE,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];

            $employeeUser = User::factory()->create($userData);

            $employeeUser->assignRole(UserRole::BASIC);
        });

        activity()->enableLogging();
    }
}

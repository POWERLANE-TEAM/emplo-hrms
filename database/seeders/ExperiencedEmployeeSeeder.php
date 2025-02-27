<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Arr;
use App\Models\SpecificArea;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ServiceIncentiveLeave;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ExperiencedEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->disableLogging();
        DB::beginTransaction();

        $employees = Employee::factory(10)->create();

        $data = [];

        $employees->each(function ($employee) use (&$data) {
            $this->createUser($employee);

            $employee->jobDetail()->create([
                'job_title_id'  => JobTitle::inRandomOrder()->first()->job_title_id,
                'area_id'       => SpecificArea::where('area_name', 'Head Office')->first()->area_id,
                'shift_id'      => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => fake()->randomElement([
                    EmploymentStatus::REGULAR,
                    EmploymentStatus::PROBATIONARY,
                ]),
            ]);

            $yrsInService = array_keys(array_filter(
                ServiceIncentiveLeave::silCreditsYearlyResetMap(), 
                fn ($year) => $year != 0, ARRAY_FILTER_USE_KEY)
            );

            $dateHired = $employee->application?->hired_at;

            if (! $dateHired) {
                $randKey = array_rand($yrsInService);

                $dateHired = now()->subYears($yrsInService[$randKey]);

                $employee->lifecycle()->create(['started_at' => $dateHired->copy()->addWeek()]);
                $employee->jobDetail()->update(['hired_at' => $dateHired]);
            }

            $quarter = match (true) {
                in_array($dateHired->month, ServiceIncentiveLeave::getFirstQuarter()) => ServiceIncentiveLeave::Q1,
                in_array($dateHired->month, ServiceIncentiveLeave::getSecondQuarter()) => ServiceIncentiveLeave::Q2,
                in_array($dateHired->month, ServiceIncentiveLeave::getThirdQuarter()) => ServiceIncentiveLeave::Q3,
                in_array($dateHired->month, ServiceIncentiveLeave::getFourthQuarter()) => ServiceIncentiveLeave::Q4,
            };

            $data[] = [
                'employee_id' => $employee->employee_id,
                'vacation_leave_credits' => $quarter->value,
                'sick_leave_credits' => $quarter->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        DB::table('service_incentive_leave_credits')->insert($data);

        DB::commit();
        activity()->enableLogging();
    }

    private function createUser(Employee $employee)
    {
        $file = File::json(base_path('resources/js/email-domain-list.json'));
        $freeEmailDomain = $file['valid_email'];
        $validDomains = Arr::random($freeEmailDomain);

        $userData = [
            'account_type' => AccountType::EMPLOYEE,
            'account_id' => $employee->employee_id,
            'email' => 'basic.'.fake()->unique()->userName().'@'.$validDomains,
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => UserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employeeUser = User::factory()->create($userData);

        $employeeUser->assignRole(UserRole::BASIC);
    }
}

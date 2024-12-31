<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Employee;
use App\Models\EmployeeDoc;
use App\Models\EmployeeJobDetail;
use App\Models\EmploymentStatus;
use App\Models\JobTitle;
use App\Models\JobVacancy;
use App\Models\PreempRequirement;
use App\Models\Shift;
use App\Models\SpecificArea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

function createEmployee($chunkStart, $chunk, $freeEmailDomain)
{

    activity()->withoutLogs(function () use ($chunkStart, $chunk, $freeEmailDomain) {
        try {
            $chunkEnd = $chunkStart + $chunk;
            Log::info("Creating applicants from $chunkStart to $chunkEnd\n");
            for ($i = $chunkStart; $i < $chunkEnd; $i++) {
                DB::transaction(function () use ($i, $freeEmailDomain) {
                    try {

                        $applicant = Applicant::factory()->create([
                            'created_at' => fake()->dateTimeBetween('-5 years', 'now'),
                        ]);

                        $employee = Employee::factory()->create();

                        $validDomains = Arr::random($freeEmailDomain);

                        $timestamp = fake()->dateTimeBetween('-5 years', 'now');

                        $userData = [
                            'account_type' => AccountType::EMPLOYEE,
                            'account_id' => $employee->employee_id,
                            'email' => fake()->randomElement(UserRole::values()) . '.' . str_pad($i, 3, '0', STR_PAD_LEFT) . '@' . $validDomains,
                            'password' => Hash::make('UniqP@ssw0rd'),
                            'user_status_id' => UserStatus::ACTIVE,
                            'email_verified_at' => $timestamp->modify('+' . rand(1, 7) . ' days'),
                            'created_at' => $timestamp,
                        ];

                        $employeeUser = User::factory()->create($userData);

                        $employeeUser->assignRole(fake()->randomElement(UserRole::values()));

                        $application = Application::create([
                            'applicant_id' => $applicant->applicant_id,
                            'job_vacancy_id' => JobVacancy::inRandomOrder()->first()->job_vacancy_id,
                            'application_status_id' => ApplicationStatus::APPROVED,
                            'is_passed' => true,
                            'hired_at' => $timestamp->modify('+' . rand(3, 14) . ' days'),
                        ]);

                        PreempRequirement::all()->each(function ($requirement) use ($application) {
                            $application->documents()->create([
                                'preemp_req_id' => $requirement->preemp_req_id,
                                'file_path' => 'storage/',
                            ]);
                        });

                        EmployeeJobDetail::create([
                            'employee_id' => $employee->employee_id,
                            'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
                            'area_id' => SpecificArea::inRandomOrder()->first()->area_id,
                            'shift_id' => Shift::inRandomOrder()->first()->shift_id,
                            'emp_status_id' => EmploymentStatus::inRandomOrder()->first()->emp_status_id,
                            'application_id' => $application->application_id,
                        ]);

                        EmployeeDoc::create([
                            'employee_id' => $employee->employee_id,
                            'file_path' => 'storage/',
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
                    }
                });
            }
        } catch (\Throwable $th) {
            Log::error('Exception: ' . $th->getMessage() . ' in ' . $th->getFile() . ' on line ' . $th->getLine());
        }

        return ['result' => true];
    });
}

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?int $count = null, ?int $start = null, ?int $concurrencyCount = null): void
    {
        $count = $count ?? env('APP_USER_SEEDING_COUNT', 30);

        $start = $start ?? Employee::max('employee_id') + 1;

        $file = File::json(base_path('resources/js/email-domain-list.json'));
        $freeEmailDomain = $file['valid_email'];

        $concurrencyCount = $concurrencyCount ?? env('APP_MAX_CONCURRENT_COUNT', 10);
        $chunkCount = ceil($count / $concurrencyCount);
        Log::info("Total $count chunk $chunkCount\n");

        Applicant::unguard();
        Application::unguard();
        Employee::unguard();
        EmployeeDoc::unguard();

        $tasks = [];
        for ($i = 0; $i < $concurrencyCount; $i++) {
            $chunkStart = $start + ($chunkCount * $i);
            $tasks[] = fn() => createEmployee($chunkStart, $chunkCount, $freeEmailDomain);
        }

        Concurrency::run($tasks);

        Applicant::reguard();
        Application::reguard();
        Employee::reguard();
        EmployeeDoc::reguard();
    }
}

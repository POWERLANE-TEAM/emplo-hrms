<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\ApplicationStatus;
use App\Enums\FilePath;
use App\Enums\UserPermission;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\ApplicationDoc;
use App\Models\ApplicationExam;
use App\Models\Employee;
use App\Models\FinalInterview;
use App\Models\InitialInterview;
use App\Models\JobVacancy;
use App\Models\User;
use App\Traits\NeedsEmptyPdf;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

function createApplicants($chunkStart, $chunk, $permissions)
{
    activity()->withoutLogs(function () use ($chunkStart, $chunk) {
        try {
            $chunkEnd = $chunkStart + $chunk;

            for ($i = $chunkStart; $i < $chunkEnd; $i++) {
                DB::transaction(function () use ($i) {
                    try {
                        $applicant = Applicant::factory()->create([
                            'created_at' => fake()->dateTimeBetween('-5 years', 'now'),
                        ]);

                        $users_data = [
                            'account_type' => AccountType::APPLICANT,
                            'account_id' => $applicant->applicant_id,
                            'email' => 'applicant.'.str_pad($i, 3, '0', STR_PAD_LEFT).'@gmail.com',
                            'password' => Hash::make('UniqP@ssw0rd'),
                            'user_status_id' => EnumUserStatus::ACTIVE,
                            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
                        ];

                        $applicant_user = User::factory()->create($users_data);

                        $applicantStatus = fake()->randomElement(ApplicationStatus::values());

                        $application = Application::create([
                            'applicant_id' => $applicant->applicant_id,
                            'job_vacancy_id' => JobVacancy::inRandomOrder()->first()->job_vacancy_id,
                            'application_status_id' => $applicantStatus,
                        ]);

                        $randomFileName = 'resume_'.uniqid().'.pdf';

                        $pdfGen = new class
                        {
                            use NeedsEmptyPdf;
                        };

                        [$resumePath, $pdf] = $pdfGen->emptyPdf(FilePath::RESUME->value, $randomFileName, '<h1>Sample Resume</h1>');

                        Storage::disk('public')->put($resumePath, $pdf->output());

                        ApplicationDoc::create([
                            'application_id' => $application->application_id,
                            'file_path' => $resumePath,
                            'preemp_req_id' => 17, // resume
                        ]);

                        if (in_array($applicantStatus, array_map(fn ($status) => $status->value, array_merge(ApplicationStatus::qualifiedState(), [ApplicationStatus::PRE_EMPLOYED])))) {
                            $examTime = Carbon::instance(fake()->dateTimeBetween('1 days', '2 days'));
                            $interviewTime = $examTime->addDays(fake()->numberBetween(0, 5));
                            ApplicationExam::create([
                                'application_id' => $application->application_id,
                                'start_time' => $examTime,
                                'end_time' => $examTime->addMinutes(30),
                            ]);

                            InitialInterview::create([
                                'application_id' => $application->application_id,
                                'init_interview_at' => $interviewTime,
                                'init_interviewer' => Employee::whereHas('jobTitle', function ($query) {
                                    $query->where('job_title', 'like', '%hr%');
                                })->inRandomOrder()->firstOr(fn () => Employee::inRandomOrder()->first())->employee_id,
                            ]);

                            if ($applicantStatus == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value) {
                                $finalInterviewTime = fake()->dateTimeBetween('5 days', '7 days');
                                FinalInterview::create([
                                    'application_id' => $application->application_id,
                                    'final_interview_at' => $finalInterviewTime,
                                    'final_interviewer' => Employee::whereHas('jobTitle', function ($query) {
                                        $query->where('job_title', 'like', '%hr%');
                                    })->inRandomOrder()->firstOr(fn () => Employee::inRandomOrder()->first())->employee_id,
                                ]);
                            }

                            if ($applicantStatus == ApplicationStatus::PRE_EMPLOYED->value) {
                                $finalInterviewTime = fake()->dateTimeBetween('5 days', '7 days');
                                FinalInterview::create([
                                    'application_id' => $application->application_id,
                                    'final_interview_at' => $finalInterviewTime,
                                    'final_interviewer' => Employee::whereHas('jobTitle', function ($query) {
                                        $query->where('job_title', 'like', '%hr%');
                                    })->inRandomOrder()->firstOr(fn () => Employee::inRandomOrder()->first())->employee_id,
                                    'is_final_interview_passed' => true,
                                    'is_job_offer_accepted' => true,
                                ]);
                            }
                        }

                        // $applicant_user->givePermissionTo($permissions);
                    } catch (\Exception $e) {
                        Log::error('Exception: '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
                    }
                });
            }
        } catch (\Throwable $th) {
            Log::error('Exception: '.$th->getMessage().' in '.$th->getFile().' on line '.$th->getLine());
        }

        return ['result' => true];
    });
}

/**
 * Seeder class for a Applicants account with roles and permissions.
 *
 * @method void run() - Seeds the applicants table with initial data.
 *
 * @param  int|null  $count  The number of seeds to create. Default is 1.
 * @param  int|null  $start  The starting point for seeding. Default is 0.
 * @param  int|null  $concurrencyCount  The number of concurrent processes. Default is 10.
 *                                      - Adjust if 10 concurrent processes is too much for your device.
 * @return void
 */
class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Adjust if 10 concurrent processes are too much for your device.
     *
     * @param  int|null  $count  The number of seeds to create. Default is 1.
     * @param  int|null  $start  The starting point for seeding. Default is 0.
     * @param  int|null  $concurrencyCount  The number of concurrent processes. Default is 10.
     */
    public function run(?int $count = null, ?int $start = null, ?int $concurrencyCount = null): void
    {
        $count ??= env('APP_USER_SEEDING_COUNT', 70);

        if ($count > 100) {
            $count = $count / 2;
        }

        $start ??= Applicant::max('applicant_id') + 1;

        $concurrencyCount ??= env('APP_MAX_CONCURRENT_COUNT', 10);
        $chunkCount = ceil($count / $concurrencyCount);

        $permissions = [
            UserPermission::CREATE_PRE_EMPLOYMENT_DOCUMENT,
            UserPermission::DELETE_PRE_EMPLOYMENT_DOCUMENT,
        ];

        Application::unguard();

        $tasks = [];
        for ($i = 0; $i < $concurrencyCount; $i++) {
            $chunkStart = $start + ($chunkCount * $i);
            $tasks[] = fn () => createApplicants($chunkStart, $chunkCount, $permissions);
        }

        Concurrency::run($tasks);

        Application::reguard();
    }
}

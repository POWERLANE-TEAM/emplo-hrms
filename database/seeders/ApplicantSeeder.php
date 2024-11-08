<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Models\User;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobVacancy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


function createApplicants($chunkStart, $chunk, $permissions)
{
    try {
        $chunkEnd = $chunkStart + $chunk;
        Log::info("Creating applicants from $chunkStart to $chunkEnd\n");
        for ($i = $chunkStart; $i < $chunkEnd; $i++) {
            DB::transaction(function () use ($i, $permissions) {
                try {
                    $applicant = Applicant::factory()->create([
                        'created_at' => fake()->dateTimeBetween('-5 years', 'now'),
                    ]);

                    $users_data = [
                        'account_type' => AccountType::APPLICANT,
                        'account_id' => $applicant->applicant_id,
                        'email' => 'applicant.' . str_pad($i, 3, '0', STR_PAD_LEFT) . '@gmail.com',
                        'password' => Hash::make('UniqP@ssw0rd'),
                        'user_status_id' => EnumUserStatus::ACTIVE,
                        'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
                    ];

                    $applicant_user = User::factory()->create($users_data);

                    Application::create([
                        'applicant_id' => $applicant->applicant_id,
                        'job_vacancy_id' => JobVacancy::inRandomOrder()->first()->job_vacancy_id,
                        'application_status_id' => ApplicationStatus::APPROVED,
                    ]);

                    $applicant_user->givePermissionTo($permissions);
                } catch (\Exception $e) {
                    Log::error('Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
                }
            });
        }
    } catch (\Throwable $th) {
        Log::error('Exception: ' . $th->getMessage() . ' in ' . $th->getFile() . ' on line ' . $th->getLine());
    }
    return ['result' => true];
}

/**
 * Seeder class for a Applicants account with roles and permissions.
 *
 * @method void run() - Seeds the applicants table with initial data.
 *
 *
 *    @param int|null $count The number of seeds to create. Default is 1.
 *    @param int|null $start The starting point for seeding. Default is 0.
 *    @param int|null $concurrencyCount The number of concurrent processes. Default is 10.
 *    - Adjust if 10 concurrent processes is too much for your device.
 *
 * @return void
 */
class ApplicantSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * Adjust if 10 concurrent processes are too much for your device.
     *
     * @param int|null $count The number of seeds to create. Default is 1.
     * @param int|null $start The starting point for seeding. Default is 0.
     * @param int|null $concurrencyCount The number of concurrent processes. Default is 10.
     *
     * @return void
     */
    public function run(?int $count = 1, ?int $start = 0, ?int $concurrencyCount = null): void
    {
        $concurrencyCount = $concurrencyCount ?? env('APP_MAX_CONCURRENT_COUNT', 10);
        $chunkCount = ceil($count / $concurrencyCount);
        Log::info("Total $count chunk $chunkCount\n");

        $permissions = [
            UserPermission::CREATE_PRE_EMPLOYMENT_DOCUMENT,
            UserPermission::UPDATE_OWNED_PRE_EMPLOYMENT_DOCUMENT,
            UserPermission::DELETE_OWNED_PRE_EMPLOYMENT_DOCUMENT,
        ];

        Application::unguard();

        $tasks = [];
        for ($i = 0; $i < $concurrencyCount; $i++) {
            $chunkStart = $start + ($chunkCount * $i);
            $tasks[] = fn() => createApplicants($chunkStart, $chunkCount, $permissions);
        }

        Concurrency::run($tasks);

        Application::reguard();
    }
}

<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\ApplicationStatus;
use App\Enums\GuardType;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\User;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobVacancy;
use Google\Service\AdMob\App;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder class for a HR Manager account with roles and permissions.
 */
class ApplicantSeeder extends Seeder
{

    const PRE_EMPLOYMENT_PERMISSIONS = [
        UserPermission::CREATE_PRE_EMPLOYMENT_DOCUMENT,
        UserPermission::UPDATE_OWNED_PRE_EMPLOYMENT_DOCUMENT,
        UserPermission::DELETE_OWNED_PRE_EMPLOYMENT_DOCUMENT,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::transaction(function () {
            $applicant = Applicant::factory()->create();

            $users_data = [
                'account_type' => AccountType::APPLICANT,
                'account_id' => $applicant->applicant_id,
                'email' => 'applicant.001@gmail.com',
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

            $role = Role::firstOrCreate(['name' => UserRole::BASIC]);
            $applicant_user->assignRole($role);

            $applicant_user->givePermissionTo(self::PRE_EMPLOYMENT_PERMISSIONS);
        });
    }
}

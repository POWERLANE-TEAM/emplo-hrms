<?php

namespace Database\Seeders;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Models\Applicant;
use App\Models\User;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobDetail;
use App\Models\JobFamily;
use App\Models\Department;
use App\Models\JobVacancy;
use App\Models\UserStatus;
use App\Models\SpecificArea;
use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        UserStatus::factory(3)->create();

        $this->call(RolesAndPermissionsSeeder::class);

        $this->call(PsgcSeeder::class);

        EmploymentStatus::factory(10)->create();

        Department::factory(rand(5, 15))->create();

        $this->call(JobTitleSeeder::class);

        JobLevel::factory(rand(5, 15))->create();

        JobFamily::factory(rand(5, 20))->create();

        SpecificArea::factory(rand(10, 25))->create();

        JobDetail::factory(rand(5, 20))->create();

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                $user->assignRole(UserRole::BASIC);
                $user->givePermissionTo([
                    UserPermission::VIEW_APPICANT_INFORMATION,
                    UserPermission::VIEW_EMPLOYEE_INFORMATION,
                ]);
            });

        $applicant = Applicant::factory()->create();

        $applicant_user =    User::factory()->create([
            'account_type' => 'applicant',
            'account_id' => $applicant->applicant_id,
            'email' => 'applicant.001@gmail.com',
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => 1,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ]);

        $applicant_user->assignRole(UserRole::BASIC);
        $applicant_user->givePermissionTo([
            UserPermission::VIEW_APPICANT_INFORMATION,
        ]);

        $employees = collect();
        $usersData = [];

        for ($i = 0; $i < 2; $i++) {
            $employee = Employee::factory()->create();
            $employees->push($employee);

            $usersData[] = [
                'account_type' => 'employee',
                'account_id' => $employees[$i]->employee_id,
                'email' => $i === 0 ? 'hr.001@gmail.com' : 'admin.001@gmail.com',
                'password' => Hash::make('UniqP@ssw0rd'),
                'user_status_id' => 1,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];

            User::factory()->create($usersData[$i]);
        }

        JobVacancy::factory(25)->create();

        $this->call(PreempRequirementSeeder::class);

        $this->call(ApplicationStatusSeeder::class);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserStatusSeeder::class);

        $this->call(ApplicationStatusSeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);

        $this->call(PsgcSeeder::class);

        $this->call(EmploymentStatusSeeder::class);

        $this->call(DepartmentSeeder::class);

        $this->call(JobTitleSeeder::class);

        $this->call(JobLevelSeeder::class);

        $this->call(SpecificAreaSeeder::class);

        $this->call(JobFamilySeeder::class);

        $this->call(BasicRoleSeeder::class);

        $this->call(IntermediateRoleSeeder::class);

        $this->call(AdvancedRoleSeeder::class);

        $this->call(JobVacancySeeder::class);

        $this->call(PreempRequirementSeeder::class);

        $this->call(ApplicantSeeder::class, false, ['count' => env('APP_USER_SEEDING_COUNT', 100)]);

        $this->call(UserSeeder::class);

        $this->call(ShiftSeeder::class);
    }
}

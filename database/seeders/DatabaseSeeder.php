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
        activity()->withoutLogs(function () {

            $this->call(UserStatusSeeder::class);

            $this->call(ApplicationStatusSeeder::class);

            $this->call(RolesAndPermissionsSeeder::class);

            $this->call(ShiftSeeder::class);

            $this->call(PsgcSeeder::class);

            $this->call(EmploymentStatusSeeder::class);

            $this->call(DepartmentSeeder::class);

            $this->call(JobLevelSeeder::class);

            $this->call(SpecificAreaSeeder::class);

            $this->call(JobFamilySeeder::class);

            $this->call(JobTitleSeeder::class);

            $this->call(BasicUserSeeder::class);

            $this->call(IntermediateUserSeeder::class);

            $this->call(AdvancedUserSeeder::class);

            $this->call(JobVacancySeeder::class);

            $this->call(PreempRequirementSeeder::class);

            $this->call(PerformanceCategorySeeder::class);

            $this->call(PerformanceRatingSeeder::class);

            $this->call(PerformancePeriodSeeder::class);

            // $this->call(EmployeeSeeder::class, false, ['count' => env('APP_USER_SEEDING_COUNT', 30)]);

            // $this->call(ApplicantSeeder::class, false, ['count' => env('APP_USER_SEEDING_COUNT', 30)]);
        });
    }
}

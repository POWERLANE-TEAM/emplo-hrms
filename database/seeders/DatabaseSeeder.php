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

            $this->call([
                PsgcSeeder::class,
                UserStatusSeeder::class,
                ApplicationStatusSeeder::class,
                RolesAndPermissionsSeeder::class,
                ShiftSeeder::class,
                EmploymentStatusSeeder::class,
                DepartmentSeeder::class,
                JobLevelSeeder::class,
                SpecificAreaSeeder::class,
                JobFamilySeeder::class,
                JobTitleSeeder::class,
                BasicUserSeeder::class,
                IntermediateUserSeeder::class,
                AdvancedUserSeeder::class,
                JobVacancySeeder::class,
                PreempRequirementSeeder::class,
                PerformanceCategorySeeder::class,
                PerformanceRatingSeeder::class,
                PerformancePeriodSeeder::class,
                HolidaySeeder::class,
            ]);
            
            // $this->call(EmployeeSeeder::class, false, ['count' => env('APP_USER_SEEDING_COUNT', 30)]);

            // $this->call(ApplicantSeeder::class, false, ['count' => env('APP_USER_SEEDING_COUNT', 30)]);
        });
    }
}

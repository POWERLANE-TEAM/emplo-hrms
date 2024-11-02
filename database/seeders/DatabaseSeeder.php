<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\JobDetail;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobVacancy;
use App\Models\SpecificArea;
use Illuminate\Database\Seeder;
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

        $this->call(UserStatusSeeder::class);

        $this->call(ApplicationStatusSeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);

        $this->call(PsgcSeeder::class);

        EmploymentStatus::factory(10)->create();

        Department::factory(rand(5, 15))->create();

        $this->call(JobTitleSeeder::class);

        JobLevel::factory(rand(5, 15))->create();

        JobFamily::factory(rand(5, 20))->create();

        SpecificArea::factory(rand(10, 25))->create();

        JobDetail::factory(rand(5, 20))->create();

        $this->call(BasicRoleSeeder::class);

        $this->call(IntermediateRoleSeeder::class);

        $this->call(AdvancedRoleSeeder::class);

        JobVacancy::factory(25)->create();

        $this->call(PreempRequirementSeeder::class);

        $this->call(ApplicantSeeder::class);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

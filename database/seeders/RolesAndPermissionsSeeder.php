<?php

namespace Database\Seeders;

// Refer to: https://spatie.be/docs/laravel-permission/v6/advanced-usage/seeding

use App\Enums\UserPermission;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect([]);

        $allRoles = $permissions->concat($this->basicPermissions())
            ->concat($this->intermediatePermissions())
            ->concat($this->advancedPermissions());

        $allRoles->each(function (string $name) {
            Permission::firstOrCreate([
                'name' => $name,
            ]);
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => UserRole::BASIC])
            ->givePermissionTo($this->basicPermissions());

        Role::firstOrCreate(['name' => UserRole::INTERMEDIATE])
            ->givePermissionTo($this->intermediatePermissions());

        Role::firstOrCreate(['name' => UserRole::ADVANCED])
            ->givePermissionTo($this->advancedPermissions());
    }

    public static function basicPermissions()
    {
        return [
            UserPermission::VIEW_EMPLOYEE_DASHBOARD->value,
            UserPermission::VIEW_ATTENDANCE->value,
            UserPermission::VIEW_PAYSLIPS->value,
            UserPermission::VIEW_PERFORMANCE->value,
            UserPermission::VIEW_LEAVES->value,
            UserPermission::VIEW_OVERTIME->value,
            UserPermission::VIEW_DOCUMENTS->value,
            UserPermission::VIEW_ISSUES->value,
        ];
    }
    public static function intermediatePermissions()
    {
        return [
            // View cases goes here
            UserPermission::VIEW_HR_MANAGER_DASHBOARD->value,
            UserPermission::VIEW_ALL_APPLICANTS->value,
            UserPermission::VIEW_ALL_EMPLOYEES->value,
            UserPermission::VIEW_ALL_ATTENDANCE->value,
            UserPermission::VIEW_ALL_LEAVES->value,
            UserPermission::VIEW_ALL_OVERTIME->value,
            UserPermission::VIEW_ALL_PAYSLIPS->value,
            UserPermission::VIEW_ALL_PERFORMANCE->value,
            UserPermission::VIEW_ALL_RELATIONS->value,
            UserPermission::VIEW_MATRIX_PROJECTOR->value,
            UserPermission::VIEW_TALENT_EVALUATOR->value,
            UserPermission::VIEW_PLAN_GENERATOR->value,


            // Create cases goes here
            UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE->value,
            UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE->value,


            // Update cases goes here
            UserPermission::UPDATE_APPLICATION_STATUS->value,


            // Delete cases goes here
        ];
    }

    public static function advancedPermissions()
    {
        return [
            // View cases goes here
            UserPermission::VIEW_ADMIN_DASHBOARD->value,
            UserPermission::VIEW_CALENDAR_MANAGER->value,
            UserPermission::VIEW_ACCOUNT_MANAGER->value,
            UserPermission::VIEW_EMPLOYEE_MANAGER->value,
            UserPermission::VIEW_JOB_LISTING_MANAGER->value,
            UserPermission::VIEW_POLICY_MANAGER->value,
            UserPermission::VIEW_ANNOUNCEMENT_MANAGER->value,
            UserPermission::VIEW_PERFORMANCE_CONFIG->value,
            UserPermission::VIEW_FORM_CONFIG->value,
            UserPermission::VIEW_ONLINE_USERS->value,

            // Create cases goes here
            UserPermission::CREATE_JOB_LISTING->value,
            UserPermission::CREATE_ANNOUNCEMENT->value,
            UserPermission::CREATE_EMPLOYEE_ACCOUNT->value,
            UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT->value,

            // Update cases goes here


            // Delete cases goes here
        ];
    }
}

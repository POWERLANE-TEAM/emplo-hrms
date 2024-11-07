<?php

namespace Database\Seeders;

// Refer to: https://spatie.be/docs/laravel-permission/v6/advanced-usage/seeding

use App\Enums\GuardType;
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

        /*
         * Define permissions here using backed enums in Permissions
         */

        /*
        * Using default web guard
        */

        // create permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::CREATE_PRE_EMPLOYMENT_DOCUMENT]);

        // view permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::VIEW_APPLICANT_INFORMATION]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_EMPLOYEE_INFORMATION]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_EMPLOYEE_DASHBOARD]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_HR_MANAGER_DASHBOARD]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_APPLICANTS]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_EMPLOYEES]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_ATTENDANCE]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_LEAVES]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_OVERTIME]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_PAYSLIPS]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_PERFORMANCE]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ALL_RELATIONS]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_MATRIX_PROJECTOR]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_TALENT_EVALUATOR]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_PLAN_GENERATOR]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_CALENDAR_MANAGER]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ACCOUNT_MANAGER]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_EMPLOYEE_MANAGER]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_JOB_LISTING_MANAGER]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_POLICY_MANAGER]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ANNOUNCEMENT_MANAGER]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_PERFORMANCE_CONFIG]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_FORM_CONFIG]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_ADMIN_DASHBOARD]);

        // update permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::UPDATE_OWNED_PRE_EMPLOYMENT_DOCUMENT]);
        Permission::firstOrCreate(['name' => UserPermission::UDPATE_JOB_LISTING]);
        Permission::firstOrCreate(['name' => UserPermission::UPDATE_ANNOUNCEMENT]);

        // create permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::CREATE_JOB_LISTING]);
        Permission::firstOrCreate(['name' => UserPermission::CREATE_ANNOUNCEMENT]);
        Permission::firstOrCreate(['name' => UserPermission::CREATE_EMPLOYEE_ACCOUNT]);
        Permission::firstOrCreate(['name' => UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT]);

        // delete permissions here
        Permission::firstOrCreate(['name' => UserPermission::DELETE_OWNED_PRE_EMPLOYMENT_DOCUMENT]);
        Permission::firstOrCreate(['name' => UserPermission::DELETE_JOB_LISTING]);
        Permission::firstOrCreate(['name' => UserPermission::DELETE_ANNOUNCEMENT]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
         * Define user roles with default permissions here using backed enums in Roles
         */
        Role::create(['name' => UserRole::BASIC]);
        Role::create(['name' => UserRole::INTERMEDIATE]);
        Role::create(['name' => UserRole::ADVANCED]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

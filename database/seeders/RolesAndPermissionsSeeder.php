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


        // view permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::VIEW_APPLICANT_INFORMATION]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_EMPLOYEE_INFORMATION]);

        // update permissions goes here

        // delete permissions here

        /*
         * Using employee guard
         */

        // create permissions goes here
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::CREATE_JOB_LISTING]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::CREATE_ANNOUNCEMENT]);

        // view permissions goes here
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_APPLICANT_INFORMATION]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_EMPLOYEE_INFORMATION]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_EMPLOYEE_DASHBOARD]);

        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_HR_MANAGER_DASHBOARD]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_APPLICANTS]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_EMPLOYEES]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_ATTENDANCE]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_LEAVES]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_OVERTIME]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_PAYSLIPS]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_PERFORMANCE]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_ALL_RELATIONS]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_MATRIX_PROJECTOR]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_TALENT_EVALUATOR]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::VIEW_PLAN_GENERATOR]);

        // update permissions goes here
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::UDPATE_JOB_LISTING]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::UPDATE_ANNOUNCEMENT]);

        // delete permissions here
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::DELETE_JOB_LISTING]);
        Permission::firstOrCreate(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserPermission::DELETE_ANNOUNCEMENT]);

        /*
         * Using admin guard
         */

        // create permissions goes here
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::CREATE_JOB_LISTING]);
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::CREATE_ANNOUNCEMENT]);
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::CREATE_EMPLOYEE_ACCOUNT]);
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT]);

        // view permissions goes here
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::VIEW_ADMIN_DASHBOARD]);
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::VIEW_CALENDAR_MANAGEMENT]);

        // update permissions goes here
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::UDPATE_JOB_LISTING]);
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::UPDATE_ANNOUNCEMENT]);

        // delete permissions here
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::DELETE_JOB_LISTING]);
        Permission::firstOrCreate(['guard_name' => GuardType::ADMIN->value, 'name' => UserPermission::DELETE_ANNOUNCEMENT]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
         * Define user roles with default permissions here using backed enums in Roles
         */

        // basic level permissions goes here
        $basic = Role::create(['name' => UserRole::BASIC]);
        $basic->givePermissionTo([
            UserPermission::VIEW_APPLICANT_INFORMATION,
            UserPermission::VIEW_EMPLOYEE_INFORMATION,
        ]);

        // intermediate level permissions goes here
        $intermediate = Role::create(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserRole::INTERMEDIATE]);
        $intermediate->givePermissionTo([
            UserPermission::VIEW_APPLICANT_INFORMATION,
            UserPermission::VIEW_EMPLOYEE_INFORMATION,
            UserPermission::VIEW_EMPLOYEE_DASHBOARD,
            UserPermission::CREATE_JOB_LISTING,
            UserPermission::CREATE_ANNOUNCEMENT,
        ]);

        // advanced level permissions goes here
        $advanced = Role::create(['guard_name' => GuardType::ADMIN->value, 'name' => UserRole::ADVANCED]);

        // Select permissions for admin as admin role cannot have permission that is not in admin guard
        $advance_permissions = Permission::where('guard_name', GuardType::ADMIN->value)->get();
        $advanced->givePermissionTo($advance_permissions);
    }
}

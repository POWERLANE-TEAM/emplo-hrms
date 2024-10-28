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

        // update permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::UPDATE_OWNED_PRE_EMPLOYMENT_DOCUMENT]);

        // delete permissions here
        Permission::firstOrCreate(['name' => UserPermission::DELETE_OWNED_PRE_EMPLOYMENT_DOCUMENT]);


        $permissions = [

            /**
             * Using employee guard
             */
            GuardType::EMPLOYEE->value => [

                /**
                 * create permissions goes here
                 */
                UserPermission::CREATE_JOB_LISTING,
                UserPermission::CREATE_ANNOUNCEMENT,
                UserPermission::CREATE_EMPLOYEE_ACCOUNT,
                UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT,

                /**
                 * view permissions goes here
                 */
                UserPermission::VIEW_APPLICANT_INFORMATION,
                UserPermission::VIEW_EMPLOYEE_INFORMATION,
                UserPermission::VIEW_EMPLOYEE_DASHBOARD,
                UserPermission::VIEW_HR_MANAGER_DASHBOARD,
                UserPermission::VIEW_ALL_APPLICANTS,
                UserPermission::VIEW_ALL_EMPLOYEES,
                UserPermission::VIEW_ALL_ATTENDANCE,
                UserPermission::VIEW_ALL_LEAVES,
                UserPermission::VIEW_ALL_OVERTIME,
                UserPermission::VIEW_ALL_PAYSLIPS,
                UserPermission::VIEW_ALL_PERFORMANCE,
                UserPermission::VIEW_ALL_RELATIONS,
                UserPermission::VIEW_MATRIX_PROJECTOR,
                UserPermission::VIEW_TALENT_EVALUATOR,
                UserPermission::VIEW_PLAN_GENERATOR,
                UserPermission::VIEW_CALENDAR_MANAGER,
                UserPermission::VIEW_ACCOUNT_MANAGER,
                UserPermission::VIEW_EMPLOYEE_MANAGER,
                UserPermission::VIEW_JOB_LISTING_MANAGER,
                UserPermission::VIEW_POLICY_MANAGER,
                UserPermission::VIEW_ANNOUNCEMENT_MANAGER,
                UserPermission::VIEW_PERFORMANCE_CONFIG,
                UserPermission::VIEW_FORM_CONFIG,

                /**
                 * update permissions goes here
                 */
                UserPermission::UDPATE_JOB_LISTING,
                UserPermission::UPDATE_ANNOUNCEMENT,

                /**
                 * delete permissions goes here
                 */
                UserPermission::DELETE_JOB_LISTING,
                UserPermission::DELETE_ANNOUNCEMENT,
            ],

            /*
            * Using admin guard
            */
            GuardType::ADMIN->value => [

                /**
                 * create permissions goes here
                 */
                UserPermission::CREATE_JOB_LISTING,
                UserPermission::CREATE_ANNOUNCEMENT,
                UserPermission::CREATE_EMPLOYEE_ACCOUNT,
                UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT,

                /**
                 * view permissions goes here
                 */
                UserPermission::VIEW_APPLICANT_INFORMATION,
                UserPermission::VIEW_EMPLOYEE_INFORMATION,
                UserPermission::VIEW_EMPLOYEE_DASHBOARD,
                UserPermission::VIEW_HR_MANAGER_DASHBOARD,
                UserPermission::VIEW_ALL_APPLICANTS,
                UserPermission::VIEW_ALL_EMPLOYEES,
                UserPermission::VIEW_ALL_ATTENDANCE,
                UserPermission::VIEW_ALL_LEAVES,
                UserPermission::VIEW_ALL_OVERTIME,
                UserPermission::VIEW_ALL_PAYSLIPS,
                UserPermission::VIEW_ALL_PERFORMANCE,
                UserPermission::VIEW_ALL_RELATIONS,
                UserPermission::VIEW_MATRIX_PROJECTOR,
                UserPermission::VIEW_TALENT_EVALUATOR,
                UserPermission::VIEW_PLAN_GENERATOR,
                UserPermission::VIEW_ADMIN_DASHBOARD,
                UserPermission::VIEW_CALENDAR_MANAGER,
                UserPermission::VIEW_ACCOUNT_MANAGER,
                UserPermission::VIEW_EMPLOYEE_MANAGER,
                UserPermission::VIEW_JOB_LISTING_MANAGER,
                UserPermission::VIEW_POLICY_MANAGER,
                UserPermission::VIEW_ANNOUNCEMENT_MANAGER,
                UserPermission::VIEW_PERFORMANCE_CONFIG,
                UserPermission::VIEW_FORM_CONFIG,

                /**
                 * update permissions goes here
                 */
                UserPermission::UDPATE_JOB_LISTING,
                UserPermission::UPDATE_ANNOUNCEMENT,

                /**
                 * delete permissions goes here
                 */
                UserPermission::DELETE_JOB_LISTING,
                UserPermission::DELETE_ANNOUNCEMENT,
            ],
        ];

        foreach ($permissions as $guard => $permission_list) {
            foreach ($permission_list as $permission) {
                Permission::firstOrCreate(['guard_name' => $guard, 'name' => $permission]);
            }
        }

        /*
         * Define user roles with default permissions here using backed enums in Roles
         */
        Role::create(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserRole::BASIC]);
        Role::create(['guard_name' => GuardType::EMPLOYEE->value, 'name' => UserRole::INTERMEDIATE]);
        Role::create(['guard_name' => GuardType::ADMIN->value, 'name' => UserRole::ADVANCED]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

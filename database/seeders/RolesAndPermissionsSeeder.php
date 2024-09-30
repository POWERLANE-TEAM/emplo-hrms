<?php

namespace Database\Seeders;

// Refer to: https://spatie.be/docs/laravel-permission/v6/advanced-usage/seeding

use App\Enums\UserPermission;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        /*
         * Define permissions here using backed enums in Permissions             
         */

        // create permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::CREATE_JOB_LISTING]);
        Permission::firstOrCreate(['name' => UserPermission::CREATE_ANNOUNCEMENT]);

        // view permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::VIEW_APPICANT_INFORMATION]);
        Permission::firstOrCreate(['name' => UserPermission::VIEW_EMPLOYEE_INFORMATION]);

        
        // update permissions goes here
        Permission::firstOrCreate(['name' => UserPermission::UDPATE_JOB_LISTING]);
        Permission::firstOrCreate(['name' => UserPermission::UPDATE_ANNOUNCEMENT]);


        // delete permissions here
        Permission::firstOrCreate(['name' => UserPermission::DELETE_JOB_LISTING]);
        Permission::firstOrCreate(['name' => UserPermission::DELETE_ANNOUNCEMENT]);


        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        /*
         * Define user roles with default permissions here using backed enums in Roles                                                              
         */

        // basic level permissions goes here
        $basic = Role::create(['name' => UserRole::BASIC]);
        $basic->givePermissionTo([
            UserPermission::VIEW_APPICANT_INFORMATION,
            UserPermission::VIEW_EMPLOYEE_INFORMATION,
        ]);

        // intermediate level permissions goes here
        $edit = Role::create(['name' => UserRole::INTERMEDIATE]);
        $edit->givePermissionTo([
            UserPermission::VIEW_APPICANT_INFORMATION,
            UserPermission::VIEW_EMPLOYEE_INFORMATION,
            UserPermission::CREATE_JOB_LISTING,
            UserPermission::CREATE_ANNOUNCEMENT,
        ]);

        // advanced level permissions goes here
        $manage = Role::create(['name' => UserRole::ADVANCED]);
        $manage->givePermissionTo(Permission::all());
    }
}

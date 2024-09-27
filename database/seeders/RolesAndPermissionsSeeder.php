<?php

namespace Database\Seeders;

// Refer to: https://spatie.be/docs/laravel-permission/v6/advanced-usage/seeding

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::firstOrCreate(['name' => 'create announcement']);

        Permission::firstOrCreate(['name' => 'view announcement']);

        Permission::firstOrCreate(['name' => 'edit announcement']);

        Permission::firstOrCreate(['name' => 'delete announcement']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        // guest role and permissions
        $guest = Role::create(['name' => 'guest']);
        $guest->givePermissionTo('view announcement');

        // user role and permissions
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'view announcement',
            'edit announcement'
        ]);

        // system administrator role and permissions
        $sysadmin = Role::create(['name' => 'sysadmin']);
        $sysadmin->givePermissionTo(Permission::all());
    }
}

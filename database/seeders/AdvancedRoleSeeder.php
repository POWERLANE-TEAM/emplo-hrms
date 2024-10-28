<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\GuardType;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder class for a Admin account with roles and permissions.
 */
class AdvancedRoleSeeder extends Seeder
{

    /**
     * Additional permissions for the admin account.
     *
     * @var array
     *
     * View cases, create cases, update cases, and delete cases will be defined here.
     */
    const ADVANCED_PERMISSIONS = [
        // View cases goes here
        UserPermission::VIEW_ADMIN_DASHBOARD,
        UserPermission::VIEW_CALENDAR_MANAGER,
        UserPermission::VIEW_ACCOUNT_MANAGER,
        UserPermission::VIEW_EMPLOYEE_MANAGER,
        UserPermission::VIEW_JOB_LISTING_MANAGER,
        UserPermission::VIEW_POLICY_MANAGER,
        UserPermission::VIEW_ANNOUNCEMENT_MANAGER,
        UserPermission::VIEW_PERFORMANCE_CONFIG,
        UserPermission::VIEW_FORM_CONFIG,

        // Create cases goes here
        UserPermission::CREATE_JOB_LISTING,
        UserPermission::CREATE_ANNOUNCEMENT,
        UserPermission::CREATE_EMPLOYEE_ACCOUNT,
        UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT,

        // Update cases goes here


        // Delete cases goes here

    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::factory()->create();

        $userData = [
            'account_type' => AccountType::EMPLOYEE,
            'account_id' => $employee->employee_id,
            'email' => 'advanced.' . fake()->unique()->safeEmail(),
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => EnumUserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employeeUser = User::factory()->create($userData);

        $role = Role::firstOrCreate(['name' => UserRole::ADVANCED, 'guard_name' => GuardType::ADMIN->value]);
        $employeeUser->assignRole($role);

        $permissions = collect(self::ADVANCED_PERMISSIONS)
            ->map(fn($permission) => Permission::where('name', $permission)
                ->where('guard_name', GuardType::ADMIN->value)
                ->first());

        $employeeUser->givePermissionTo($permissions);
    }
}

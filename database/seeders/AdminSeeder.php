<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\GuardType;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder class for a Admin account with roles and permissions.
 */
class AdminSeeder extends Seeder
{

    /**
     * Additional permissions for the admin account.
     *
     * @var array
     *
     * View cases, create cases, update cases, and delete cases will be defined here.
     */
    const ADDITIONAL_PERMISSIONS = [
        // View cases goes here


        // Create cases goes here


        // Update cases goes here


        // Delete cases goes here

    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $employee = Employee::factory()->create();

        $users_data = [
            'account_type' => AccountType::EMPLOYEE,
            'account_id' => $employee->employee_id,
            'email' => 'admin.001@gmail.com',
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => EnumUserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employee_user = User::factory()->create($users_data);

        $role = Role::firstOrCreate(['name' => UserRole::ADVANCED, 'guard_name' => GuardType::ADMIN->value]);
        $employee_user->assignRole($role);
    }

    /**
     * Give the seeded admin account extra permissions aside from the ADVANCE role.
     *
     * @param \App\Models\User $employee_user The user to whom the extra permissions will be given.
     * @return void
     */
    private function GiveExtraPermissions(User $employee_user)
    {
        $permissions = collect(self::ADDITIONAL_PERMISSIONS)
            ->map(fn($permission) => Permission::where('name', $permission)
                ->where('guard_name', GuardType::ADMIN->value)
                ->first());

        $employee_user->givePermissionTo($permissions);
    }
}

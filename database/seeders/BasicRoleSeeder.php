<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\GuardType;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\User;
use App\Enums\UserStatus as EnumUserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder class for Employee Account with roles and permissions.
 */
class BasicRoleSeeder extends Seeder
{

    const BASIC_PERMISSIONS = [
        UserPermission::VIEW_EMPLOYEE_DASHBOARD,
        UserPermission::VIEW_ATTENDANCE,
        UserPermission::VIEW_PAYSLIPS,
        UserPermission::VIEW_PERFORMANCE,
        UserPermission::VIEW_LEAVES,
        UserPermission::VIEW_OVERTIME,
        UserPermission::VIEW_DOCUMENTS,
        UserPermission::VIEW_ISSUES
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
            'email' => 'emp.001@gmail.com',
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => EnumUserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employeeUser = User::factory()->create($userData);

        $employeeUser->assignRole(UserRole::BASIC);

        $permissions = collect(self::BASIC_PERMISSIONS)
            ->map(fn($permission) => Permission::where('name', $permission)
                ->first());

        $employeeUser->givePermissionTo($permissions);
    }
}

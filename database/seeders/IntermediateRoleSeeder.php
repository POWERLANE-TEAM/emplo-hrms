<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\GuardType;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\User;
use App\Enums\UserStatus as EnumUserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder class for a HR Manager account with roles and permissions.
 */
class IntermediateRoleSeeder extends Seeder
{

    const INTERMEDIATE_PERMISSIONS = [
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
            'email' => 'intermediate' . fake()->unique()->safeEmail(),
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => EnumUserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employeeUser = User::factory()->create($userData);

        $employeeUser->assignRole(UserRole::INTERMEDIATE);

        $permissions = collect(self::INTERMEDIATE_PERMISSIONS)
            ->map(fn($permission) => Permission::where('name', $permission)
                ->first());

        $employeeUser->givePermissionTo($permissions);
    }
}

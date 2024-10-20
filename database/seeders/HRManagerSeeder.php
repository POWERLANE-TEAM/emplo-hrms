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
 * Seeder class for a HR Manager account with roles and permissions.
 */
class HRManagerSeeder extends Seeder
{

    const HR_MANAGER_PERMISSIONS = [
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

        DB::transaction(function () {
            $employee = Employee::factory()->create();

            $users_data = [
                'account_type' => AccountType::EMPLOYEE,
                'account_id' => $employee->employee_id,
                'email' => 'hr.001@gmail.com',
                'password' => Hash::make('UniqP@ssw0rd'),
                'user_status_id' => EnumUserStatus::ACTIVE,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];

            $employee_user = User::factory()->create($users_data);

            $role = Role::firstOrCreate(['name' => UserRole::INTERMEDIATE, 'guard_name' => GuardType::EMPLOYEE->value]);
            $employee_user->assignRole($role);

            $permissions = collect(self::HR_MANAGER_PERMISSIONS)
                ->map(fn($permission) => Permission::where('name', $permission)
                    ->where('guard_name', GuardType::EMPLOYEE->value)
                    ->first());

            $employee_user->givePermissionTo($permissions);
        });
    }
}

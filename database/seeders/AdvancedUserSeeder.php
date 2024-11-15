<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\UserRole;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder class for a Admin account with roles and permissions.
 */
class AdvancedUserSeeder extends Seeder
{
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

        $employeeUser->assignRole(UserRole::ADVANCED);
    }
}

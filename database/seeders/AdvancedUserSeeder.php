<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\UserRole;
use App\Enums\UserStatus;
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
            'email' => config('auth.advanced_user_username'),
            'password' => Hash::make(config('auth.advanced_user_password')),
            'user_status_id' => UserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employeeUser = User::factory()->create($userData);

        $employeeUser->assignRole(UserRole::ADVANCED);
    }
}

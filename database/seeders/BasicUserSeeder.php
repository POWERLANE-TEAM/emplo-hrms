<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\User;
use App\Enums\UserStatus as EnumUserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder class for Employee Account with roles and permissions.
 */
class BasicUserSeeder extends Seeder
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
            'email' => 'emp.001@gmail.com',
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => EnumUserStatus::ACTIVE,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ];

        $employeeUser = User::factory()->create($userData);

        $employeeUser->assignRole(UserRole::BASIC);
    }
}
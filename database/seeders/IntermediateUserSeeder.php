<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\UserRole;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder class for a HR Manager account with roles and permissions.
 */
class IntermediateUserSeeder extends Seeder
{
    protected static $freeEmailDomain = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $employee = Employee::factory()->create();

            $file = File::json(base_path('resources/js/email-domain-list.json'));
            self::$freeEmailDomain = $file['valid_email'];
            $validDomains = Arr::random(self::$freeEmailDomain);
    
            $userData = [
                'account_type' => AccountType::EMPLOYEE,
                'account_id' => $employee->employee_id,
                'email' => 'intermediate'.fake()->unique()->userName().'@'.$validDomains,
                'password' => Hash::make('UniqP@ssw0rd'),
                'user_status_id' => EnumUserStatus::ACTIVE,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];
    
            $employeeUser = User::factory()->create($userData);
    
            $employeeUser->assignRole(UserRole::INTERMEDIATE);
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ManagerialSeeder extends Seeder
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
                'email' => 'managerial.'.fake()->unique()->userName().'@'.$validDomains,
                'password' => Hash::make('UniqP@ssw0rd'),
                'user_status_id' => UserStatus::ACTIVE,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];

            $employeeUser = User::factory()->create($userData);

            $employeeUser->assignRole(UserRole::BASIC);
            $employeeUser->givePermissionTo(RolesAndPermissionsSeeder::managerialPermissions());            
        });
    }
}

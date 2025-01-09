<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Arr;
use App\Models\SpecificArea;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class HrStaffSeeder extends Seeder
{
    protected static $freeEmailDomain = [];
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $employee = Employee::factory()->create();

            $employee->jobDetail()->updateOrCreate([
                'job_title_id'  => JobTitle::where('job_title', 'HR Staff')->first()->job_title_id,
                'area_id'       => SpecificArea::where('area_name', 'Head Office')->first()->area_id,
                'shift_id'      => Shift::inRandomOrder()->first()->shift_id,
                'emp_status_id' => EmploymentStatus::REGULAR,
            ]);

            $file = File::json(base_path('resources/js/email-domain-list.json'));
            self::$freeEmailDomain = $file['valid_email'];
            $validDomains = Arr::random(self::$freeEmailDomain);
    
            $userData = [
                'account_type'      => AccountType::EMPLOYEE,
                'account_id'        => $employee->employee_id,
                'email'             => 'hrstaff.employee.'.fake()->userName()."f@{$validDomains}",
                'password'          => Hash::make('UniqP@ssw0rd'),
                'user_status_id'    => UserStatus::ACTIVE,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];
    
            $employeeUser = User::factory()->create($userData);
    
            $employeeUser->assignRole(UserRole::BASIC);
            $employeeUser->givePermissionTo(RolesAndPermissionsSeeder::hrStaffPermissions());
        });
    }
}

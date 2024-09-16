<?php

namespace Database\Seeders;

use App\Models\CompanyDoc;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDoc;
use App\Models\EmploymentStatus;
use App\Models\JobDetail;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobVacancy;
use App\Models\SpecificArea;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user_roles = UserRole::factory()->predefinedUserRoles();

        foreach ($user_roles as $user_role) {
            UserRole::create($user_role);
        }

        $user_statuses = UserStatus::factory()->predefinedUserStatuses();

        foreach ($user_statuses as $user_status) {
            UserStatus::create($user_status);
        }

        EmploymentStatus::factory(10)->create();

        Department::factory(rand(5, 15))->create();

        $this->call(JobTitleSeeder::class);
        JobLevel::factory(rand(5, 15))->create();
        JobFamily::factory(rand(5, 20))->create();
        SpecificArea::factory(rand(10, 25))->create();
        JobDetail::factory(rand(5, 20))->create();
        User::factory(8)->create();

        $employees = collect();

        for ($i = 0; $i < 2; $i++) {
            $employee = Employee::factory()->create([
                'first_name' => fake()->firstName,
                'middle_name' => fake()->firstName,
                'last_name' => fake()->lastName,
                'job_detail_id' => JobDetail::inRandomOrder()->first()->job_detail_id ?? 1,
                'hired_at' => fake()->dateTimeThisDecade,
                'emp_status_id' => EmploymentStatus::inRandomOrder()->first()->emp_status_id ?? 1,
                'present_address' => fake()->address,
                'permanent_address' => fake()->address,
                'contact_number' => fake()->unique()->numerify('###########'),
                'photo' => fake()->optional()->imageUrl(),
                'sex' => fake()->randomElement(['MALE', 'FEMALE']),
                'civil_status' => fake()->randomElement(['SINGLE', 'MARRIED', 'WIDOWED', 'LEGALLY SEPARATED']),
                'sss_no' => fake()->numerify('##########'),
                'philhealth_no' => fake()->numerify('############'),
                'tin_no' => fake()->numerify('############'),
                'pag_ibig_no' => fake()->numerify('############'),
                'signature' => fake()->sha256,
                'education' => fake()->word,
            ]);

            $employees->push($employee);
        }

        User::factory()->create([
            'account_type' => 'employee',
            'account_id' => $employees[0]->employee_id,
            'email' => 'hr.001@gmail.com',
            'password' =>  Hash::make('UniqP@ssw0rd'),
            'user_role_id' => 4,
            'user_status_id' => 1,
            'email_verified_at' => fake()->dateTimeBetween('-10days', 'now'),
        ]);

        User::factory()->create([
            'account_type' => 'employee',
            'account_id' => $employees[1]->employee_id,
            'email' => 'admin.001@gmail.com',
            'password' =>  Hash::make('UniqP@ssw0rd'),
            'user_role_id' => 5,
            'user_status_id' => 1,
            'email_verified_at' => fake()->dateTimeBetween('-10days', 'now'),
        ]);

        JobVacancy::factory(25)->create();

        $documents = CompanyDoc::factory()->predefinedDocuments();

        foreach ($documents as $document) {
            CompanyDoc::create($document);
        }
    }
}

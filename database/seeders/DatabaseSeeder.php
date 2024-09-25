<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\CompanyDoc;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDoc;
use App\Models\EmploymentStatus;
use App\Models\JobDetail;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobVacancy;
use App\Models\PreempRequirement;
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

        $applicant = Applicant::factory()->create();

        User::factory()->create([
            'account_type' => 'applicant',
            'account_id' => $applicant->applicant_id,
            'email' => 'applicant.001@gmail.com',
            'password' => Hash::make('UniqP@ssw0rd'),
            'user_status_id' => 1,
            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ]);

        $employees = collect();
        $users_data = [];

        for ($i = 0; $i < 2; $i++) {
            $employee = Employee::factory()->create();
            $employees->push($employee);

            $users_data[] = [
                'account_type' => 'employee',
                'account_id' => $employees[$i]->employee_id,
                'email' => $i === 0 ? 'hr.001@gmail.com' : 'admin.001@gmail.com',
                'password' => Hash::make('UniqP@ssw0rd'),
                'user_status_id' => 1,
                'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
            ];

            User::factory()->create($users_data[$i]);
        }

        JobVacancy::factory(25)->create();

        $application_statuses = ApplicationStatus::factory()->predefinedStatuses();

        foreach ($application_statuses as $application_status) {
            ApplicationStatus::create($application_status);
        }

        Application::create([
            'applicant_id' => $applicant->applicant_id,
            'job_vacancy_id' => JobVacancy::inRandomOrder()->first()->job_vacancy_id,
            'application_status_id' => ApplicationStatus::inRandomOrder()->first()->application_status_id,
        ]);

        $preemp_reqs = PreempRequirement::factory()->predefinedDocuments();

        foreach ($preemp_reqs as $preemp_req) {
            PreempRequirement::create($preemp_req);
        }
    }
}

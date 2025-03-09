<?php

namespace Database\Seeders;

use App\Enums\EmploymentStatus;
use App\Enums\TrainingStatus;
use App\Models\Employee;
use App\Models\OutsourcedTrainer;
use App\Models\Training;
use App\Models\TrainingProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = TrainingProvider::all();

        $employees = Employee::whereHas('status', function ($query) {
            $query->where('emp_status_name', EmploymentStatus::PROBATIONARY->label())
                ->orWhere('emp_status_name', EmploymentStatus::REGULAR->label());
        })->get();

        $outsourcedTrainers = [];

        foreach ($providers as $provider) {
            $outsourcedTrainers = array_merge(
                $outsourcedTrainers,
                OutsourcedTrainer::factory()->count(3)->create([
                    'training_provider' => $provider->training_provider_id,
                ])->all()
            );
        }

        $trainers = $employees->merge($outsourcedTrainers);

        foreach ($employees as $employee) {
            $randomTrainer = $trainers->random();

            if ($randomTrainer instanceof Employee) {
                $trainerType = 'employee';
                $trainerId = $randomTrainer->employee_id;
            } else {
                $trainerType = 'outsourced_trainer';
                $trainerId = $randomTrainer['trainer_id'];
            }

            Training::create([
                'trainee' => $employee->employee_id,
                'trainer_type' => $trainerType,
                'trainer_id' => $trainerId,
                'prepared_by' => $employees->random()->employee_id,
                'reviewed_by' => $employees->random()->employee_id,
                'start_date' => Carbon::now()->subDays(rand(31, 60)),
                'end_date' => Carbon::now()->subDays(rand(1, 30)),
                'expiry_date' => Carbon::now()->addDays(rand(30, 365)),
                'training_title' => fake()->sentence,
                'description' => fake()->paragraph,
                'completion_status' => fake()->randomElement(array_map(fn ($case) => $case->value, TrainingStatus::cases())),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Training;
use App\Enums\TrainingStatus;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Illuminate\Database\Seeder;
use App\Models\TrainingProvider;
use App\Models\OutsourcedTrainer;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all the training providers
        $providers = TrainingProvider::all();
    
        // Get employees who are either probationary or regular
        $employees = Employee::whereHas('status', function ($query) {
            $query->where('emp_status_name', EmploymentStatus::PROBATIONARY->label())
                  ->orWhere('emp_status_name', EmploymentStatus::REGULAR->label());
        })->get();
    
        // Array to hold outsourced trainers
        $outsourcedTrainers = [];
    
        // Create 3 outsourced trainers per provider and add them to the $outsourcedTrainers array
        foreach ($providers as $provider) {
            $outsourcedTrainers = array_merge(
                $outsourcedTrainers,
                OutsourcedTrainer::factory()->count(3)->create([
                    'training_provider' => $provider->training_provider_id,
                ])->all()
            );
        }
    
        // Combine employees and outsourced trainers into a single array
        $trainers = $employees->merge($outsourcedTrainers);
    
        // For each employee, randomly assign a trainer from the combined $trainers array
        foreach ($employees as $employee) {
            // Pick a random trainer (could be an employee or outsourced trainer)
            $randomTrainer = $trainers->random();
    
            // Determine if the trainer is an Employee or Outsourced Trainer
            if ($randomTrainer instanceof Employee) {
                $trainerType = 'employee';
                $trainerId = $randomTrainer->employee_id;
            } else {
                $trainerType = 'outsourced_trainer';
                $trainerId = $randomTrainer['trainer_id'];
            }
    
            // Create the training record
            Training::create([
                'trainee' => $employee->employee_id,
                'trainer_type' => $trainerType,
                'trainer_id' => $trainerId,
                'prepared_by' => $employees->random()->employee_id,
                'reviewed_by' => $employees->random()->employee_id,
                'training_date' => Carbon::now()->subDays(rand(1, 30)),
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

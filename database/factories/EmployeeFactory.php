<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName,
            'middle_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'position_id' => Position::inRandomOrder()->first()->position_id ?? 1,
            'branch_id' => Branch::inRandomOrder()->first()->branch_id ?? 1,
            'department_id' => Department::inRandomOrder()->first()->department_id ?? 1,
            'hired_at' => fake()->dateTimeThisDecade,
            'emp_status_id' => EmploymentStatus::inRandomOrder()->first()->emp_status_id ?? 1,
            'present_address' => fake()->address,
            'permanent_address' => fake()->address,
            'contact_number' => fake()->numerify('###########'),
            'photo' => fake()->optional()->imageUrl(),
            'sex' => fake()->randomElement(['MALE', 'FEMALE']),
            'civil_status' => fake()->randomElement(['SINGLE', 'MARRIED', 'WIDOWED', 'LEGALLY SEPARATED']),
            'sss_no' => fake()->numerify('##########'),
            'philhealth_no' => fake()->numerify('############'),
            'tin_no' => fake()->numerify('############'),
            'pag_ibig_no' => fake()->numerify('############'),
            'signature' => fake()->sha256,
            'education' => fake()->word,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\JobDetail;
use App\Models\EmploymentStatus;
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
            'job_detail_id' => JobDetail::factory(),
            'hired_at' => fake()->dateTimeThisDecade,
            'emp_status_id' => EmploymentStatus::factory(),
            'present_barangay' => fake()->randomElement(['0102801001', '0102802001']),
            'permanent_barangay' => fake()->randomElement(['0102802002', '0102802003']),
            'present_address' => fake()->address,
            'permanent_address' => fake()->address,
            'contact_number' => fake()->unique()->numerify('###########'),
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

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applicant>
 */
class ApplicantFactory extends Factory
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
            'contact_number' => fake()->unique()->numerify('###########'),
            'sex' => fake()->randomElement(['MALE', 'FEMALE']),
            'civil_status' => fake()->randomElement(['SINGLE', 'MARRIED', 'WIDOWED', 'LEGALLY SEPARATED']),
            'present_barangay' => fake()->randomNumber(1, 9),
            'permanent_barangay' => fake()->randomNumber(1, 9),
            'present_address' => fake()->streetName(),
            'permanent_address' => fake()->streetName(),
            'date_of_birth' => fake()->date,
        ];
    }
}

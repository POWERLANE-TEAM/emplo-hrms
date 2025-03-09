<?php

namespace Database\Factories;

use App\Enums\CivilStatus;
use App\Enums\Sex;
use App\Models\Barangay;
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
            'present_barangay' => Barangay::inRandomOrder()->first()->id,
            'permanent_barangay' => Barangay::inRandomOrder()->first()->id,
            'present_address' => fake()->streetName(),
            'permanent_address' => fake()->streetName(),
            'contact_number' => fake()->unique()->numerify('09#########'),
            'date_of_birth' => fake()->date,
            'sex' => fake()->randomElement(array_map(fn ($case) => $case->value, Sex::cases())),
            'civil_status' => fake()->randomElement(array_map(fn ($case) => $case->value, CivilStatus::cases())),
            'sss_no' => fake()->numerify('##########'),
            'philhealth_no' => fake()->numerify('############'),
            'tin_no' => fake()->numerify('############'),
            'pag_ibig_no' => fake()->numerify('############'),
            'signature' => fake()->sha256,
        ];
    }
}

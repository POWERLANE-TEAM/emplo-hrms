<?php

namespace Database\Factories;

use App\Enums\CivilStatus;
use App\Enums\Sex;
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
        $educationCount = fake()->numberBetween(1, 3);
        $experienceCount = fake()->numberBetween(1, 5);

        $education = [];
        for ($i = 0; $i < $educationCount; $i++) {
            $education[] = [
                'degree' => fake()->randomElement([
                    'Bachelor of ' . fake()->word . ' Studies',
                    'Bachelor of ' . fake()->word . ' Science',
                    'Bachelor of ' . fake()->word . ' Engineering',
                    'Master of ' . fake()->word . ' Science',
                ]),
                'institution' => fake()->company,
                'year' => fake()->year,
            ];
        }

        $experience = [];
        for ($i = 0; $i < $experienceCount; $i++) {
            $experience[] = [
                'company' => fake()->company,
                'position' => fake()->jobTitle,
                'years' => fake()->numberBetween(1, 10),
            ];
        }

        return [
            'first_name' => fake()->firstName,
            'middle_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'present_barangay' => fake()->randomNumber(1, 9),
            'permanent_barangay' => fake()->randomNumber(1, 9),
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
            'education' => json_encode($education),
            'experience' => json_encode($experience),
        ];
    }
}

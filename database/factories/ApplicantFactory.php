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

        $educationCount = fake()->numberBetween(1, 3); // Random count between 1 and 5
        $experienceCount = fake()->numberBetween(1, 5); // Random count between 1 and 5

        $education = [];
        for ($i = 0; $i < $educationCount; $i++) {
            $education[] = [
                'degree' => fake()->word,
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
            'contact_number' => fake()->unique()->numerify('###########'),
            'education' => json_encode($education),
            'experience' => json_encode($experience),
        ];
    }
}

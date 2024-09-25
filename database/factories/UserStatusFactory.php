<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserStatus>
 */
class UserStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_status_name' => $this->faker->randomElement(['ACTIVE', 'INACTIVE', 'BLOCKED']),
            'user_status_desc' => fake()->paragraph(255),
        ];
    }

    public function predefinedUserStatuses()
    {
        return [
            ['user_status_name' => 'ACTIVE', 'user_status_desc' => fake()->paragraph(255)],
            ['user_status_name' => 'INACTIVE', 'user_status_desc' => fake()->paragraph(255)],
            ['user_status_name' => 'BLOCKED', 'user_status_desc' => fake()->paragraph(255)],
            // Add more documents as needed
        ];
    }
}

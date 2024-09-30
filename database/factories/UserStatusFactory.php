<?php

namespace Database\Factories;

use App\Enums\UserStatus;
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
            'user_status_name' => $this->faker->randomElement([
                UserStatus::ACTIVE,
                UserStatus::INACTIVE,
                UserStatus::SUSPENDED,
            ]),

            'user_status_desc' => fake()->paragraph(255),
        ];
    }
}

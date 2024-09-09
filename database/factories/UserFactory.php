<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->randomElement([
                fake()->safeEmail(),
                fake()->freeEmail(),
            ]),
            'role' => $this->faker->randomElement(['GUEST', 'USER', 'MANAGER', 'SYSADMIN']),
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE', 'BLOCKED']),
            'password' => static::$password ??= Hash::make('p@ssw0rd'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn(array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }
}

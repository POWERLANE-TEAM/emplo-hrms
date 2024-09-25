<?php

namespace Database\Factories;

use App\Models\Applicant;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\UserStatus;
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
            'account_type' => $this->faker->randomElement(['applicant', 'employee']),
            'account_id' => function (array $attributes) {
                if ($attributes['account_type'] === 'employee') {
                    $employee = Employee::factory()->create();

                    return $employee->employee_id;
                }

                if ($attributes['account_type'] === 'applicant') {
                    $applicant = Applicant::factory()->create();

                    return $applicant->applicant_id;
                }

                return fake()->randomDigitNotNull();
            },
            'email' => fake()->unique()->randomElement([
                fake()->safeEmail(),
                fake()->freeEmail(),
            ]),
            'password' => static::$password ??= Hash::make('UniqP@ssw0rd'),
            'user_status_id' => UserStatus::inRandomOrder()->first()->user_status_id ?? 1,
            'email_verified_at' => fake()->unique()->randomElement([
                null,
                fake()->dateTimeBetween('-10days', 'now'),
            ]),
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

<?php

namespace Database\Factories;

use App\Enums\AccountType;
use App\Models\Applicant;
use App\Models\Employee;
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
            'account_type' => $this->faker->randomElement([AccountType::APPLICANT->value, AccountType::EMPLOYEE->value]),
            'account_id' => function (array $attributes) {
                $accountType = AccountType::from($attributes['account_type']);

                return match ($accountType) {
                    AccountType::EMPLOYEE => Employee::factory()->create()->employee_id,
                    AccountType::APPLICANT => Applicant::factory()->create()->applicant_id,
                };

                return fake()->randomDigitNotNull();
            },
            'email' => fake()->unique()->randomElement([
                fake()->safeEmail(),
                fake()->freeEmail(),
            ]),
            'password' => static::$password ??= Hash::make('UniqP@ssw0rd'),
            'user_status_id' => $this->faker->randomElement([1, 2, 3]),
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

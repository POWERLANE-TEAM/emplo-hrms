<?php

namespace Database\Factories;

use App\Enums\BiometricPunchType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceLog>
 */
class AttendanceLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(array_map(fn ($case) => $case->value, BiometricPunchType::cases()));
    
        $timestamp = match ($type) {
            BiometricPunchType::CHECK_IN->value => fake()->dateTimeBetween('8:00', '10:00')->format('Y-m-d H:i:s'),
            BiometricPunchType::CHECK_OUT->value => fake()->dateTimeBetween('16:00', '18:00')->format('Y-m-d H:i:s'),
            BiometricPunchType::OVERTIME_IN->value => fake()->dateTimeBetween('18:00', '20:00')->format('Y-m-d H:i:s'),
            BiometricPunchType::OVERTIME_OUT->value => fake()->dateTimeBetween('20:00', '23:00')->format('Y-m-d H:i:s'),
        };
    
        return [
            'uid' => fake()->randomNumber(),
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'state' => fake()->numberBetween(1, 9),
            'type' => $type,
            'timestamp' => $timestamp,
        ];
    }    
}

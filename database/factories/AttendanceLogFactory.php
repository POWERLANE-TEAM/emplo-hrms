<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Enums\BiometricPunchType;
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
        $type = fake()->randomElement(
            array_map(fn ($case) => $case->value, array_filter(
                BiometricPunchType::cases(), fn ($case) => ! in_array($case, [
                    BiometricPunchType::OVERTIME_IN, BiometricPunchType::OVERTIME_OUT
                    ])
                )
            )
        );        
    
        $timestamp = match ($type) {
            BiometricPunchType::CHECK_IN->value => fake()->dateTimeBetween('5:30', '14:00')->format('Y-m-d H:i:s'),
            BiometricPunchType::CHECK_OUT->value => fake()->dateTimeBetween('13:30', '22:00')->format('Y-m-d H:i:s'),
        };
            
        return [
            'employee_id' => null,
            'state' => fake()->numberBetween(1, 9),
            'type' => $type,
            'timestamp' => $timestamp,
        ];
    }
}

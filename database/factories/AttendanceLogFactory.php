<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Enums\BiometricPunchType;
use App\Enums\EmploymentStatus;
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
            BiometricPunchType::CHECK_IN->value => fake()->dateTimeBetween('8:00', '10:00')->format('Y-m-d H:i:s'),
            BiometricPunchType::CHECK_OUT->value => fake()->dateTimeBetween('16:00', '18:00')->format('Y-m-d H:i:s'),
        };

        $uid = $this->generateUniqueUid();

        $employee = Employee::activeEmploymentStatus()->inRandomOrder()->first();
    
        return [
            'uid' => $uid,
            'employee_id' => $employee->employee_id,
            'state' => fake()->numberBetween(1, 9),
            'type' => $type,
            'timestamp' => $timestamp,
        ];
    }
    
    /**
     * Generate a unique UID that doesn't conflict with existing records.
     *
     * @return int
     */
    private function generateUniqueUid(): int
    {
        $uid = fake()->unique()->randomNumber();
        
        while (AttendanceLog::where('uid', $uid)->exists()) {
            $uid = fake()->unique()->randomNumber();
        }

        return $uid;
    }
}

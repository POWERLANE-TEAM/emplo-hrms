<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ApplicationStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function predefinedStatuses()
    {
        return [
            ['application_status_name' => 'PENDING', 'application_status_desc' => 'Pending approval'],
            ['application_status_name' => 'APPROVED', 'application_status_desc' => 'Approved application'],
            ['application_status_name' => 'REJECTED', 'application_status_desc' => 'Rejected application'],
        ];
    }
}

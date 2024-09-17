<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    protected $model = \App\Models\UserRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_role_name' => $this->faker->randomElement(['APPLICANT', 'IMM SUPERVISOR', 'HR EMP', 'HR MANAGER', 'SYSADMIN']),
            'user_role_desc' => fake()->paragraph(255),
        ];
    }

    public function predefinedUserRoles()
    {
        return [
            ['user_role_name' => 'APPLICANT', 'user_role_desc' => fake()->paragraph(255)],
            ['user_role_name' => 'IMM SUPERVISOR', 'user_role_desc' => fake()->paragraph(255)],
            ['user_role_name' => 'HR EMP', 'user_role_desc' => fake()->paragraph(255)],
            ['user_role_name' => 'HR MANAGER', 'user_role_desc' => fake()->paragraph(255)],
            ['user_role_name' => 'SYSADMIN', 'user_role_desc' => fake()->paragraph(255)],
        ];
    }
}

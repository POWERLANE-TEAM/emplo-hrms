<?php

namespace Database\Seeders;

use App\Enums\UserStatus as AccountStatus;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_statuses = [
            [
                'user_status_id' => AccountStatus::ACTIVE, 
                'user_status_name' => AccountStatus::ACTIVE->label(),
                'user_status_desc' => fake()->paragraph(),
            ],
            [
                'user_status_id' => AccountStatus::INACTIVE, 
                'user_status_name' => AccountStatus::INACTIVE->label(),
                'user_status_desc' => fake()->paragraph(),
            ],
            [
                'user_status_id' => AccountStatus::SUSPENDED, 
                'user_status_name' => AccountStatus::SUSPENDED->label(),
                'user_status_desc' => fake()->paragraph(),
            ],
        ];

        foreach ($user_statuses as $user_status) {
            UserStatus::create($user_status);
        }
    }
}

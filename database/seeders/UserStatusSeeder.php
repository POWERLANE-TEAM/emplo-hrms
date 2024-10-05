<?php

namespace Database\Seeders;

use App\Enums\UserStatus as EnumsUserStatus;
use App\Models\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_statuses = [
            ['user_status_name' => EnumsUserStatus::ACTIVE, 'user_status_desc' => EnumsUserStatus::ACTIVE->label()],
            ['user_status_name' => EnumsUserStatus::INACTIVE, 'user_status_desc' => EnumsUserStatus::INACTIVE->label()],
            ['user_status_name' => EnumsUserStatus::SUSPENDED, 'user_status_desc' => EnumsUserStatus::SUSPENDED->label()],
        ];

        foreach ($user_statuses as $user_status) {
            UserStatus::create($user_status);
        }
    }
}

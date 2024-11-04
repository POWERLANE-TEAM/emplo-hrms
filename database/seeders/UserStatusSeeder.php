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
        $userStatuses = collect([
            [
                AccountStatus::ACTIVE->value,
                AccountStatus::ACTIVE->label()
            ],
            [
                AccountStatus::SUSPENDED->value,
                AccountStatus::SUSPENDED->label()
            ],
            [
                AccountStatus::NOT_VERIFIED->value,
                AccountStatus::NOT_VERIFIED->label()
            ],
        ]);

        $userStatuses->eachSpread(function (int $id, string $label) {
            UserStatus::create([
                'user_status_id' => $id,
                'user_status_name' => $label,
            ]);
        });
    }
}

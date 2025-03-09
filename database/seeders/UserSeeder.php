<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $this->call(BasicUserSeeder::class);
            $this->call(IntermediateUserSeeder::class);
            // $this->call(AdvancedUserSeeder::class);
        });
    }
}

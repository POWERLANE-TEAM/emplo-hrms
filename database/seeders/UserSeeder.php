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
        // Seed 10 users: 5 each for advanced and intermediate users
        for ($i = 0; $i < 5; $i++) {

            $this->call(AdvancedRoleSeeder::class);
            $this->call(IntermediateRoleSeeder::class);
        }
    }
}

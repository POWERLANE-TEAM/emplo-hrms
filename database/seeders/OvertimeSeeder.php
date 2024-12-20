<?php

namespace Database\Seeders;

use App\Models\Overtime;
use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            Overtime::unguard();
            Overtime::factory(40)->create();
            Overtime::reguard();
        });
    }
}   

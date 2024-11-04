<?php

namespace Database\Seeders;

use App\Models\JobDetail;
use Illuminate\Database\Seeder;

class JobDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobDetail::factory()->create();
    }
}

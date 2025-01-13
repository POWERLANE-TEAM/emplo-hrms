<?php

namespace Database\Seeders;

use App\Models\TrainingProvider;
use Illuminate\Database\Seeder;

class TrainingProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TrainingProvider::factory(10)->create();
    }
}

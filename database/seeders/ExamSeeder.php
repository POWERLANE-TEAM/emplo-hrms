<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exam::factory(2)->create();
    }
}

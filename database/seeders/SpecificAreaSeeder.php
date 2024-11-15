<?php

namespace Database\Seeders;

use App\Models\SpecificArea;
use Illuminate\Database\Seeder;

class SpecificAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = collect([
            'Cavite',
            'Head Office',
            'NCR',
            'Pampanga',
            'Ilocos'
        ]);

        $areas->each(function (string $area) {
            SpecificArea::updateOrCreate([
                'area_name' => $area,
                'area_desc' => fake()->address(),
                'area_manager' => null,
            ]);
        });
    }
}

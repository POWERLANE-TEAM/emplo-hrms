<?php

namespace Database\Seeders;

use App\Models\Payroll;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakes = [
            [
                'cut_off_start' => '2024-10-11',
                'cut_off_end' => '2024-10-25',
                'payout' => '2024-10-31',
            ],
            [
                'cut_off_start' => '2024-10-26',
                'cut_off_end' => '2024-11-10',
                'payout' => '2024-11-15', 
            ],
            [
                'cut_off_start' => '2024-11-11',
                'cut_off_end' => '2024-11-25',
                'payout' => '2024-11-30',
            ],
            [
                'cut_off_start' => '2024-11-26',
                'cut_off_end' => '2024-12-10',
                'payout' => '2024-12-15',
            ],
            [
                'cut_off_start' => '2024-12-11',
                'cut_off_end' => '2024-12-25',
                'payout' => '2024-12-31',
            ],
            [
                'cut_off_start' => '2024-12-26',
                'cut_off_end' => '2025-01-10',
                'payout' => '2025-01-15',
            ],
            [
                'cut_off_start' => '2025-01-11',
                'cut_off_end' => '2025-01-25',
                'payout' => '2025-01-31',
            ],
        ];

        Payroll::insert($fakes);        
    }
}

<?php

namespace Database\Seeders;

use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class PayslipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payslip::factory(Employee::count())->create();
    }
}

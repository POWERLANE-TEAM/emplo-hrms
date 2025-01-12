<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contract::factory(Employee::count())->create();
    }
}

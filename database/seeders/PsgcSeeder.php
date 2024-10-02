<?php

namespace Database\Seeders;

use App\Imports\PsgcImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class PsgcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '450M');
        Excel::import(new PsgcImport, storage_path('PSGC-2Q-2024-Publication-Datafile-rev.xlsx'));
    }
}

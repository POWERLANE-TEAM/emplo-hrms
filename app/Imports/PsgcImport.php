<?php

namespace App\Imports;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class PsgcImport implements ToModel, WithBatchInserts, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'PSGC' => $this, // sheet name
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function model(array $row)
    {
        // for mapping of sheet's heading names
        $psgc = $row['10-digit PSGC'];
        $name = $row['Name'];
        $geoLevel = $row['Geographic Level'];

        if ($geoLevel === 'Reg') {
            return new Region([
                'code' => $psgc,
                'name' => $name,
                'region_code' => substr($psgc, 0, 2),
            ]);
        }

        if ($geoLevel === 'Prov') {
            return new Province([
                'code' => $psgc,
                'name' => $name,
                'province_code' => substr($psgc, 0, 5),
                'region_code' => substr($psgc, 0, 2),
            ]);
        }

        if (in_array($geoLevel, ['Mun', 'SubMun', 'City'])) {
            return new City([
                'code' => $psgc,
                'name' => $name,
                'city_code' => substr($psgc, 0, 7),
                'province_code' => substr($psgc, 0, 5),
                'region_code' => substr($psgc, 0, 2),
            ]);
        }

        if ($geoLevel === 'Bgy') {
            return new Barangay([
                'code' => $psgc,
                'name' => $name,
                'city_code' => substr($psgc, 0, 7),
                'province_code' => substr($psgc, 0, 5),
                'region_code' => substr($psgc, 0, 2),
            ]);
        }

        return null;
    }
}

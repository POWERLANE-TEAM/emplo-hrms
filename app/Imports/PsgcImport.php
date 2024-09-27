<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Region;
use App\Models\Barangay;
use App\Models\Province;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class PsgcImport implements ToModel, WithHeadingRow, WithMultipleSheets, WithBatchInserts
{
    protected $regions = [];

    protected $provinces = [];

    protected $cities = [];

    public function sheets(): array
    {
        return [
            'PSGC' => $this, // sheet name
        ];
    }

    public function batchSize():int
    {
        return 1000;
    }


    public function model(array $row)
    {
        
        // for mapping of sheet's heading names
        $psgc = $row['10-digit PSGC'];

        $name = $row['Name'];

        $geo_level = $row['Geographic Level'];


        /* 
         * The offset and length are based according to the PSGC Coding Structure.
         * 
         * Reference: app\storage\PSGC-2Q-2024-Publication-Datafile-rev.xlsx
         *  
         * We can then determine jurisdictional relationships of the ff:
         * 
         * - Which region has jurisdiction to which provinces
         * - Which province has jurisdiction to which cities
         * - Which cities has jurisdiction to which barangays
         * 
         * - Carl Tabuso
         */
        $region_code = substr($psgc, 0, 2);

        $province_code = substr($psgc, 0, 5);

        $city_code = substr($psgc, 0, 8);


        // checks for region level
        if ($geo_level === 'Reg') {

            $region = new Region([
                'region_code' => $psgc,
                'region_name' => $name,
            ]);

            $this->regions[$region_code] = $region;

            return $region;
        }

        // checks for province level
        if ($geo_level === 'Prov') {

            $region = $this->regions[$region_code] ?? null;

            $province = new Province([
                'province_code' => $psgc,
                'province_name' => $name,
                'region_code' => $region->region_code ?? null,
            ]);

            $this->provinces[$province_code] = $province;

            return $province;
        }


        // checks for municipal, submunicipal, or city level
        if (in_array($geo_level, ['Mun', 'SubMun', 'City'])) {
           
            $province = $this->provinces[$province_code] ?? null;

            $city = new City([
                'city_code' => $psgc,
                'city_name' => $name,
                'province_code' => $province->province_code ?? null,
            ]);

            $this->cities[$city_code] = $city;

            return $city;                
        }

        // checks for barangay level
        if ($geo_level === 'Bgy') {

            $city = $this->cities[$city_code] ?? null;

            return new Barangay([
                'barangay_code' => $psgc,
                'barangay_name' => $name,
                'city_code' => $city->city_code ?? null,
            ]);                
        }

        return null;
    }
}

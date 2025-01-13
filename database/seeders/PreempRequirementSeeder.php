<?php

namespace Database\Seeders;

use App\Models\PreempRequirement;
use Illuminate\Database\Seeder;

class PreempRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preemp_reqs = [
            ['preemp_req_name' => 'SSS Registration Record (E-1/E-4/ID/Contribution/Emp. History)'],
            ['preemp_req_name' => 'CEDULA/ Community Tax Certificate'],
            ['preemp_req_name' => 'Barangay Clearance'],
            ['preemp_req_name' => 'Police Clearance/NBI Clearance'],
            ['preemp_req_name' => 'NSO/PSA Birthcertificate'],
            ['preemp_req_name' => 'JR./SR./HS Diploma and Form-137/HS Card (JR/SR/HS Graduate )'],
            ['preemp_req_name' => 'JR./SR./HS Diploma and TOR/Col Cert/Class cards (Col Undergrad )'],
            ['preemp_req_name' => 'JR./SR./HS Diploma and Vocational Cert/Diploma/TOR (Voc Grad )'],
            ['preemp_req_name' => 'College Diploma/TOR (College Graduate )'],
            ['preemp_req_name' => 'Certificate of Employment(s)-if previously employed'],
            ['preemp_req_name' => 'ID Picture (recent-4 copies 2x2&1x1-white T-shirt & white background)'],
            ['preemp_req_name' => "Child's Birth certificate (if applicable )"],
            ['preemp_req_name' => 'Marriage Contract (if applicable)'],
            ['preemp_req_name' => 'PAG-IBIG Registration Record (MDF/ID)'],
            ['preemp_req_name' => 'PHILHEALTH Registration Record (MDR/ID)-if previously employed'],
            ['preemp_req_name' => 'BIR Registration Record (TIN/ID)-if previously employed'],
            ['preemp_req_name' => 'Resume'],

        ];

        foreach ($preemp_reqs as $preemp_req) {
            PreempRequirement::create($preemp_req);
        }
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PreempRequirement>
 */
class PreempRequirementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function predefinedDocuments()
    {
        return [
            ['preemp_req_name' => "SSS Registration Record (E-1/E-4/ID/Contribution/Emp. History)", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "CEDULA/ Community Tax Certificate", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "Barangay Clearance", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "Police Clearance/NBI Clearance", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "NSO/PSA Birthcertificate", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "JR./SR./HS Diploma and Form-137/HS Card (JR/SR/HS Graduate )", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "JR./SR./HS Diploma and TOR/Col Cert/Class cards (Col Undergrad )", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "JR./SR./HS Diploma and Vocational Cert/Diploma/TOR (Voc Grad )", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "College Diploma/TOR (College Graduate )", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "Certificate of Employment(s) -if previously employed", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "ID Picture (recent-4 copies 2x2&1x1-white T-shirt & white background)", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "Child's Birth certificate (if applicable )", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "Marriage Contract (if applicable)", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "PAG-IBIG Registration Record (MDF/ID)", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "PHILHEALTH Registration Record (MDR/ID)-if previously employed", 'preemp_req_desc' => ''],
            ['preemp_req_name' => "BIR Registration Record (TIN/ID)-if previously employed", 'preemp_req_desc' => ''],
            // ['preemp_req_name' => "", 'preemp_req_desc' => ''],// ['document_name' => "", 'document_desc' => ''],
        ];
    }
}

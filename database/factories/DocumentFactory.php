<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
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
            ['document_name' => "SSS Registration Record (E-1/E-4/ID/Contribution/Emp. History)", 'document_desc' => ''],
            ['document_name' => "CEDULA/ Community Tax Certificate", 'document_desc' => ''],
            ['document_name' => "Barangay Clearance", 'document_desc' => ''],
            ['document_name' => "Police Clearance/NBI Clearance", 'document_desc' => ''],
            ['document_name' => "NSO/PSA Birthcertificate", 'document_desc' => ''],
            ['document_name' => "JR./SR./HS Diploma and Form-137/HS Card (JR/SR/HS Graduate )", 'document_desc' => ''],
            ['document_name' => "JR./SR./HS Diploma and TOR/Col Cert/Class cards (Col Undergrad )", 'document_desc' => ''],
            ['document_name' => "JR./SR./HS Diploma and Vocational Cert/Diploma/TOR (Voc Grad )", 'document_desc' => ''],
            ['document_name' => "College Diploma/TOR (College Graduate )", 'document_desc' => ''],
            ['document_name' => "Certificate of Employment (s)-if previously employed", 'document_desc' => ''],
            ['document_name' => "ID Picture (recent-4 copies 2x2&1x1-white T-shirt & white background)", 'document_desc' => ''],
            ['document_name' => "Child's Birth certificate (if applicable )", 'document_desc' => ''],
            ['document_name' => "Marriage Contract (if applicable)", 'document_desc' => ''],
            ['document_name' => "PAG-IBIG Registration Record (MDF/ID)", 'document_desc' => ''],
            ['document_name' => "PHILHEALTH Registration Record (MDR/ID)-if previously employed", 'document_desc' => ''],
            ['document_name' => "BIR Registration Record (TIN/ID)-if previously employed", 'document_desc' => ''],
            // ['document_name' => "", 'document_desc' => ''],
        ];
    }
}

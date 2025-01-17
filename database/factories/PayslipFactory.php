<?php

namespace Database\Factories;

use App\Enums\FilePath;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payslip>
 */
class PayslipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $month = $this->faker->monthName();
        $year = $this->faker->year();
        $name = "Payslip_{$month}_{$year}.pdf";

        Storage::disk('local')->makeDirectory(FilePath::PAYSLIPS->value);

        $file = UploadedFile::fake()->create($name, 500, 'application/pdf');
        $content = 'Testing payslip upload';
        
        $hashedName = $file->hashName();

        Storage::disk('local')->put(
            sprintf('%s/%s', FilePath::PAYSLIPS->value, $hashedName), 
            $content
        );

        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'uploaded_by' => Employee::inRandomOrder()->first()->employee_id,
            'hashed_attachment' => $hashedName,
            'attachment_name' => $file->getClientOriginalName(),
            'payroll_id' => Payroll::inRandomOrder()->first()->payroll_id
        ];
    }
}

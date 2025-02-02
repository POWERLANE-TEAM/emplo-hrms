<?php

namespace Database\Factories;

use App\Enums\ContractType;
use App\Enums\FilePath;
use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
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
        $name = "Contract{$month}_{$year}.pdf";

        Storage::disk('local')->makeDirectory(FilePath::CONTRACTS->value);

        $file = UploadedFile::fake()->create($name, 500, 'application/pdf');
        $content = 'Testing contract file upload';
        
        $hashedName = $file->hashName();

        Storage::disk('local')->put(
            sprintf('%s/%s', FilePath::CONTRACTS->value, $hashedName), 
            $content
        );

        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'type' => fake()->randomElement(array_map(fn ($case) => $case->value, ContractType::cases())),
            'uploaded_by' => Employee::inRandomOrder()->first()->employee_id,
            'hashed_attachment' => $hashedName,
            'attachment_name' => $file->getClientOriginalName(),
        ];
    }
}

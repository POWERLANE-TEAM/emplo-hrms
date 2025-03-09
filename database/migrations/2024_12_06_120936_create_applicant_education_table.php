<?php

use App\Models\Applicant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applicant_education', function (Blueprint $table) {
            $table->id('applicant_education_id');

            $table->foreignIdFor(Applicant::class, 'applicant_id')
                ->constrained('applicants', 'applicant_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('education');
            $table->timestamps();
            $table->index(['education']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_education');
    }
};

<?php

use App\Models\EducationRequirement;
use App\Models\JobTitle;
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
        Schema::create('education_requirements', function (Blueprint $table) {
            $table->id('education_req_id');
            $table->string('education_level');
            $table->string('study_field')->nullable();
            $table->timestamps();
        });

        // pivot table
        Schema::create('job_education_requirements', function (Blueprint $table) {
            $table->id('job_education_req_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(EducationRequirement::class, 'education_req_id')
                ->constrained('education_requirements', 'education_req_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_requirements');
        Schema::dropIfExists('job_education_requirements');
    }
};

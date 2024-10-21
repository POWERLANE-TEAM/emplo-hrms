<?php

use App\Models\ExperienceRequirement;
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
        Schema::create('experience_requirements', function (Blueprint $table) {
            $table->id('experience_req_id');
            $table->string('job_title')->nullable();
            $table->integer('years_of_exp')->nullable();
            $table->longText('exp_desc')->nullable();
            $table->timestamps();
        });

        // pivot table
        Schema::create('job_experience_requirements', function (Blueprint $table) {
            $table->id('job_experience_req_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(ExperienceRequirement::class, 'experience_req_id')
                ->constrained('experience_requirements', 'experience_req_id')
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
        Schema::dropIfExists('experience_requirements');
        Schema::dropIfExists('job_experience_requirements');
    }
};

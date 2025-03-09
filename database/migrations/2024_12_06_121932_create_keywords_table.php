<?php

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
        Schema::create('job_skill_keywords', function (Blueprint $table) {
            $table->id('keyword_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('keyword');
            $table->string('priority');
            $table->timestamps();
            $table->index(['keyword']);
        });

        Schema::create('job_education_keywords', function (Blueprint $table) {
            $table->id('keyword_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('keyword');
            $table->string('priority');
            $table->timestamps();
            $table->index(['keyword']);
        });

        Schema::create('job_experience_keywords', function (Blueprint $table) {
            $table->id('keyword_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('keyword');
            $table->string('priority');
            $table->timestamps();
            $table->index(['keyword']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_skill_keywords');
        Schema::dropIfExists('job_education_keywords');
        Schema::dropIfExists('job_experience_keywords');
    }
};

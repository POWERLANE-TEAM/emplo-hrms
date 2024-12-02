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
        Schema::create('job_title_qualifications', function (Blueprint $table) {
            $table->id('job_title_qual_id');
            $table->longText('job_title_qual_desc');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('priority_level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_title_qualifications');
    }
};

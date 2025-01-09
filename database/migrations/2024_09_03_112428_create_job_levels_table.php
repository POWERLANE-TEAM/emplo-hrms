<?php

use App\Models\Department;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\SpecificArea;
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
        Schema::create('job_levels', function (Blueprint $table) {
            $table->id('job_level_id');
            $table->integer('job_level')->unique();
            $table->string('job_level_name');
            $table->longText('job_level_desc')->nullable();
        });

        Schema::create('job_families', function (Blueprint $table) {
            $table->id('job_family_id');
            $table->string('job_family_name');
            $table->longText('job_family_desc')->nullable();
        });

        Schema::create('job_titles', function (Blueprint $table) {
            $table->id('job_title_id');
            $table->string('job_title');
            $table->text('job_desc')->nullable();
            $table->longText('qualification_desc')->nullable();
            $table->decimal('base_salary')->nullable();

            $table->foreignIdFor(Department::class, 'department_id')
                ->constrained('departments', 'department_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(JobLevel::class, 'job_level_id')
                ->constrained('job_levels', 'job_level_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(JobFamily::class, 'job_family_id')
                ->constrained('job_families', 'job_family_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });

        // pivot table
        Schema::create('job_details', function (Blueprint $table) {
            $table->id('job_detail_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(JobLevel::class, 'job_level_id')
                ->constrained('job_levels', 'job_level_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(JobFamily::class, 'job_family_id')
                ->constrained('job_families', 'job_family_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(SpecificArea::class, 'area_id')
                ->constrained('specific_areas', 'area_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id('job_vacancy_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->integer('vacancy_count')->default(0);
            $table->dateTime('application_deadline_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_levels');
        Schema::dropIfExists('job_titles');
        Schema::dropIfExists('job_families');
        Schema::dropIfExists('job_details');
        Schema::dropIfExists('job_vacancies');
    }
};

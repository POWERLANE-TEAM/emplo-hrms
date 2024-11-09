<?php

use App\Models\Department;
use App\Models\HardSkill;
use App\Models\JobDetail;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\SoftSkill;
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

        Schema::create('job_titles', function (Blueprint $table) {
            $table->id('job_title_id');
            $table->string('job_title');
            $table->text('job_desc')->nullable();

            $table->foreignIdFor(Department::class, 'department_id')
                ->constrained('departments', 'department_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('soft_skills', function (Blueprint $table) {
            $table->id('soft_skill_id');
            $table->string('soft_skill_name');
            $table->longText('soft_skill_desc')->nullable();
            $table->timestamps();
        });

        // pivot table
        Schema::create('job_soft_skills', function (Blueprint $table) {
            $table->id('job_soft_skill_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(SoftSkill::class, 'soft_skill_id')
                ->constrained('soft_skills', 'soft_skill_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('hard_skills', function (Blueprint $table) {
            $table->id('hard_skill_id');
            $table->string('hard_skill_name');
            $table->longText('hard_skill_desc')->nullable();
            $table->timestamps();
        });

        // pivot table
        Schema::create('job_hard_skills', function (Blueprint $table) {
            $table->id('job_hard_skill_id');

            $table->foreignIdFor(JobTitle::class, 'job_title_id')
                ->constrained('job_titles', 'job_title_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(HardSkill::class, 'hard_skill_id')
                ->constrained('hard_skills', 'hard_skill_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('job_families', function (Blueprint $table) {
            $table->id('job_family_id');
            $table->string('job_family_name');
            $table->longText('job_family_desc')->nullable();
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

            $table->foreignIdFor(JobDetail::class, 'job_detail_id')
                ->constrained('job_details', 'job_detail_id')
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
        Schema::dropIfExists('soft_skills');
        Schema::dropIfExists('job_soft_skills');
        Schema::dropIfExists('hard_skills');
        Schema::dropIfExists('job_hard_skills');
        Schema::dropIfExists('job_families');
        Schema::dropIfExists('job_details');
        Schema::dropIfExists('job_vacancies');
    }
};

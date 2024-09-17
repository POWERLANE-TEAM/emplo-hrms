<?php

use App\Models\ApplicationExam;
use App\Models\Exam;
use App\Models\Employee;
use App\Models\Applicant;
use App\Models\JobVacancy;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\PreempRequirement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application_statuses', function (Blueprint $table) {
            $table->id('application_status_id');
            $table->string('application_status_name', 100);
            $table->longText('application_status_desc')->nullable();
            $table->timestamps();
        });


        Schema::create('applications', function (Blueprint $table) {
            $table->id('application_id');

            $table->foreignIdFor(Applicant::class, 'applicant_id')
                ->constrained('applicants', 'applicant_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(JobVacancy::class, 'job_vacancy_id')
                ->constrained('job_vacancies', 'job_vacancy_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(ApplicationStatus::class, 'application_status_id')
                ->constrained('application_statuses', 'application_status_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });


        Schema::create('application_docs', function (Blueprint $table) {
            $table->id('application_doc_id');

            $table->foreignIdFor(PreempRequirement::class, 'preemp_req_id')
                ->constrained('preemp_requirements', 'preemp_req_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Application::class, 'application_id')
                ->constrained('applications', 'application_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->boolean('is_submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();

            $table->foreignIdFor(Employee::class, 'evaluated_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('application_exams', function (Blueprint $table) {
            $table->id('application_exam_id');

            $table->foreignIdFor(Exam::class, 'exam_id')
                ->constrained('exams', 'exam_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Application::class, 'application_id')
                ->constrained('applications', 'application_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
        });


        Schema::create('application_exam_results', function (Blueprint $table) {
            $table->id('exam_result_id');

            $table->foreignIdFor(ApplicationExam::class, 'application_exam_id')
                ->constrained('application_exams', 'application_exam_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            
            $table->foreignIdFor(Employee::class, 'graded_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            
            $table->integer('total_grades'); // change accordingly if required decimal values
            $table->boolean('is_passed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_statuses');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('application_docs');
        Schema::dropIfExists('application_exams');
        Schema::dropIfExists('application_exam_results');
    }
};

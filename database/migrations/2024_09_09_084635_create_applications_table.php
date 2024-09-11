<?php

use App\Models\Employee;
use App\Models\Position;
use App\Models\Applicant;
use App\Models\ApplicationStatus;
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
            $table->longText('application_status_desc');
            $table->timestamps();
        });


        Schema::create('applications', function (Blueprint $table) {
            $table->id('application_id');

            $table->foreignIdFor(Applicant::class, 'applicant_id')
                ->constrained('applicants', 'applicant_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Position::class, 'position_id')
                ->constrained('positions', 'position_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

                $table->foreignIdFor(ApplicationStatus::class, 'application_status_id')
                ->constrained('application_statuses', 'application_status_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->dateTime('exam_date')->nullable();
            $table->dateTime('init_interview_at')->nullable();

            $table->foreignIdFor(Employee::class, 'init_interviewer')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->boolean('is_init_interview_passed')->default(false);
            $table->dateTime('final_interview_at')->nullable();

            $table->foreignIdFor(Employee::class, 'final_interviewer')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->boolean('is_final_interview_passed')->default(false);
            $table->boolean('is_job_offer_accepted')->default(false);
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
    }
};

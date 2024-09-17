<?php

use App\Models\Employee;
use App\Models\Application;
use App\Models\FinalInterview;
use App\Models\InterviewParameter;
use App\Models\InterviewRating;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interview_ratings', function (Blueprint $table) {
            $table->id('rating_id');
            $table->char('rating_code', 1); // A, B, C, etc
            $table->longText('rating_desc');
            $table->timestamps();
        });


        Schema::create('interview_parameters', function (Blueprint $table) {
            $table->id('parameter_id');
            $table->longText('parameter_desc');
            $table->timestamps();
        });


        Schema::create('initial_interviews', function (Blueprint $table) {
            $table->id('init_interview_id');
            $table->dateTime('init_interview_at')->nullable();

            $table->foreignIdFor(Application::class, 'application_id')
                ->constrained('applications', 'application_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'init_interviewer')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('other_comments')->nullable();
            $table->boolean('is_init_interview_passed')->default(false);
            $table->timestamps();
        });


        Schema::create('final_interviews', function (Blueprint $table) {
            $table->id('final_interview_id');
            $table->dateTime('final_interview_at')->nullable();

            $table->foreignIdFor(Application::class, 'application_id')
                ->constrained('applications', 'application_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'final_interviewer')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('other_comments')->nullable();
            $table->boolean('is_final_interview_passed')->default(false);
            $table->boolean('is_job_offer_accepted')->default(false); // i don't know where to put this
            $table->timestamps();
        });


        Schema::create('final_interview_ratings', function (Blueprint $table) {
            $table->id('final_rating_id');
            
            $table->foreignIdFor(FinalInterview::class, 'final_interview_id')
                ->constrained('final_interviews', 'final_interview_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            
            $table->foreignIdFor(InterviewParameter::class, 'parameter_id')
                ->constrained('interview_parameters', 'parameter_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(InterviewRating::class, 'rating_id')
                ->constrained('interview_ratings', 'rating_id')
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
        Schema::dropIfExists('interview_ratings');
        Schema::dropIfExists('interview_parameters');
        Schema::dropIfExists('initial_interviews');
        Schema::dropIfExists('final_interviews');
        Schema::dropIfExists('final_interview_ratings');
    }
};

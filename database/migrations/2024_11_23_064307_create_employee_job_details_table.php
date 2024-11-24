<?php

use App\Models\Shift;
use App\Models\Employee;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\JobFamily;
use App\Models\Application;
use App\Models\SpecificArea;
use App\Models\EmploymentStatus;
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
        Schema::create('employee_job_details', function (Blueprint $table) {
            $table->id('emp_job_detail_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

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

            $table->foreignIdFor(Shift::class, 'shift_id')
                ->constrained('shifts', 'shift_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(EmploymentStatus::class, 'emp_status_id')
                ->constrained('employment_statuses', 'emp_status_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignIdFor(Application::class, 'application_id')
                ->nullable()
                ->constrained('applications', 'application_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->integer('leave_balance')->default(0);

            $table->index([
                'employee_id', 
                'job_title_id', 
                'job_level_id', 
                'job_family_id', 
                'area_id', 'shift_id', 
                'emp_status_id',
                'application_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_job_details');
    }
};

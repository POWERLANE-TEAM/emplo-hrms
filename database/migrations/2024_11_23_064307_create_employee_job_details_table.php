<?php

use App\Models\Application;
use App\Models\Employee;
use App\Models\EmployeeShift;
use App\Models\EmploymentStatus;
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

            $table->foreignIdFor(SpecificArea::class, 'area_id')
                ->constrained('specific_areas', 'area_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(EmployeeShift::class, 'shift_id')
                ->constrained('employee_shifts', 'employee_shift_id')
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

            $table->timestamp('hired_at')->nullable();

            $table->index([
                'employee_id',
                'job_title_id',
                'area_id',
                'shift_id',
                'emp_status_id',
                'application_id',
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

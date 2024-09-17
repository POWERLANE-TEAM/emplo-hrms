<?php

use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\JobDetail;
use App\Models\LeaveCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id('emp_status_id');
            $table->string('emp_status_name', 100);
            $table->longText('emp_status_desc');
            $table->timestamps();
        });


        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);

            $table->foreignIdFor(JobDetail::class, 'job_detail_id')
                ->constrained('job_details', 'job_detail_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('hired_at');

            $table->foreignIdFor(EmploymentStatus::class, 'emp_status_id')
                ->constrained('employment_statuses', 'emp_status_id')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->longText('present_address');
            $table->longText('permanent_address');
            $table->string('contact_number', 11)->unique();
            $table->string('photo')->nullable(); // emp photo file path
            $table->enum('sex', ['MALE', 'FEMALE']);
            $table->enum('civil_status', ['SINGLE', 'MARRIED', 'WIDOWED', 'LEGALLY SEPARATED']);
            $table->string('sss_no', 10)->unique();
            $table->string('philhealth_no', 12)->unique();
            $table->string('tin_no', 12)->unique();
            $table->string('pag_ibig_no', 12)->unique();
            $table->binary('signature');
            $table->string('education');
            $table->integer('leave_balance')->default(0);
            $table->timestamps();
        });

        Schema::create('employee_docs', function (Blueprint $table) {
            $table->id('emp_doc_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id('emp_leave_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(LeaveCategory::class, 'leave_id')
                ->constrained('leave_categories', 'leave_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('reason');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('is_supervisor_approved')->default(false);
            $table->timestamp('supervisor_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'supervisor')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_area_man_approved')->default(false);
            $table->timestamp('area_man_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'area_manager')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_hr_manager_approved')->default(false);
            $table->timestamp('hr_manager_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'hr_manager')
                ->constrained('employees', 'employee_id')
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
        Schema::dropIfExists('employment_statuses');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('employee_docs');
        Schema::dropIfExists('employee_leaves');
    }
};

<?php

use App\Models\Employee;
use App\Models\LeaveCategory;
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
        Schema::create('leave_categories', function (Blueprint $table) {
            $table->id('leave_category_id');
            $table->string('leave_category_name', 255);
            $table->longText('leave_category_desc')->nullable();
            $table->integer('allotted_days')->nullable();
            $table->boolean('is_proof_required')->default(false);
            $table->timestamps();
        });

        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id('emp_leave_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(LeaveCategory::class, 'leave_category_id')
                ->constrained('leave_categories', 'leave_category_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('reason');
            $table->timestamp('start_date');
            $table->timestamp('end_date');

            $table->timestamp('initial_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'initial_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('secondary_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'secondary_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('third_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'third_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('fourth_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'fourth_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('denied_at')->nullable();
            $table->foreignIdFor(Employee::class, 'denier')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('feedback')->nullable();
            $table->timestamp('filed_at');
            $table->timestamp('modified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leaves');
        Schema::dropIfExists('leave_categories');
    }
};

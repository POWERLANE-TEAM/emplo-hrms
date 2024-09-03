<?php

use App\Models\Employee;
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
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id('emp_leave_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->integer('leave_balance');

            $table->foreignIdFor(LeaveCategory::class, 'leave_id')
                ->nullable()
                ->constrained('leave_categories', 'leave_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->longText('reason');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('is_init_approved')->default(false);
            $table->timestamp('init_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'init_approved_by')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_final_approved')->default(false);
            $table->timestamp('final_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'final_approved_by')
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
        Schema::dropIfExists('employee_leaves');
    }
};

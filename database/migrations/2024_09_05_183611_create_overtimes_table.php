<?php

use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id('overtime_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('work_performed');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('hours_requested');
            $table->timestamp('filed_at');

            $table->boolean('is_supervisor_approved')->default(false);
            $table->timestamp('supervisor_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'supervisor')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_dept_head_approved')->default(false);
            $table->timestamp('dept_head_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'dept_head')
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
        Schema::dropIfExists('overtimes');
    }
};

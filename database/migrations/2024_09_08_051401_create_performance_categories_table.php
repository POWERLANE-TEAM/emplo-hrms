<?php

use App\Models\Employee;
use App\Models\PerformanceCategory;
use App\Models\PerformanceEvaluation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performance_categories', function (Blueprint $table) {
            $table->id('performance_id');
            $table->string('performance_name');
            $table->longText('performance_desc');
            $table->timestamps();
        });


        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id('perf_eval_id');

            $table->foreignIdFor(Employee::class, 'evaluatee')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->boolean('is_supervisor_signed')->default(false);
            $table->foreignIdFor(Employee::class, 'supervisor')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->boolean('is_dept_head_signed')->default(false);
            $table->foreignIdFor(Employee::class, 'dept_head')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->boolean('is_hr_manager_signed')->default(false);
            $table->foreignIdFor(Employee::class, 'hr_manager')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('perf_eval_date');
            $table->enum('perf_eval_type', ['THIRD MONTH', 'FIFTH MONTH', 'FINAL']);
            $table->boolean('is_final_recommend')->default(false)->nullable(); // recommendation to make probationary become regular
            $table->boolean('emp_acknowledgment')->default(false);
            $table->longText('employee_comments')->nullable();
            $table->longText('supervisor_comments')->nullable();
            $table->timestamps();
        });


        Schema::create('performance_evaluation_details', function (Blueprint $table) {
            $table->id('perf_eval_detail_id');

            $table->foreignIdFor(PerformanceEvaluation::class, 'perf_eval_id')
                ->nullable()
                ->constrained('performance_evaluations', 'perf_eval_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignIdFor(PerformanceCategory::class, 'performance_id')
                ->nullable()
                ->constrained('performance_categories', 'performance_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->integer('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_categories');
        Schema::dropIfExists('performance_evaluations');
        Schema::dropIfExists('performance_evaluation_details');
    }
};

<?php

use App\Models\Employee;
use App\Models\PerformanceCategory;
use App\Models\PerformanceRating;
use App\Models\ProbationaryPerformance;
use App\Models\ProbationaryPerformancePeriod;
use App\Models\RegularPerformance;
use App\Models\RegularPerformancePeriod;
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
        Schema::create('performance_categories', function (Blueprint $table) {
            $table->id('perf_category_id');
            $table->string('perf_category_name');
            $table->longText('perf_category_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('performance_ratings', function (Blueprint $table) {
            $table->id('perf_rating_id');
            $table->integer('perf_rating');
            $table->string('perf_rating_name');
            $table->timestamps();
        });

        Schema::create('probationary_performance_periods', function (Blueprint $table) {
            $table->id('period_id');
            
            $table->foreignIdFor(Employee::class, 'evaluatee')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('period_name');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('regular_performance_periods', function (Blueprint $table) {
            $table->id('period_id');
            $table->string('period_name');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamps();
        });

        Schema::create('regular_performances', function (Blueprint $table) {
            $table->id('regular_performance_id');

            $table->foreignIdFor(RegularPerformancePeriod::class, 'period_id')
                ->constrained('regular_performance_periods', 'period_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'evaluatee')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('evaluatee_comments')->nullable();
            $table->timestamp('evaluatee_signed_at')->nullable();

            $table->longText('evaluator_comments')->nullable();
            $table->timestamp('evaluator_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'evaluator')
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
            
            $table->boolean('is_employee_acknowledged')->default(false);
        });

        Schema::create('probationary_performances', function (Blueprint $table) {
            $table->id('probationary_performance_id');

            $table->foreignIdFor(ProbationaryPerformancePeriod::class, 'period_id')
                ->constrained('probationary_performance_periods', 'period_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('evaluatee_comments')->nullable();
            $table->timestamp('evaluatee_signed_at')->nullable();

            $table->longText('evaluator_comments')->nullable();
            $table->timestamp('evaluator_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'evaluator')
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

            // recommendation to make probationary become regular
            $table->boolean('is_final_recommend')->default(false);

            $table->boolean('is_employee_acknowledged')->default(false);
        });

        Schema::create('probationary_performance_ratings', function (Blueprint $table) {
            $table->foreignIdFor(PerformanceCategory::class, 'perf_category_id')
                ->constrained('performance_categories', 'perf_category_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(PerformanceRating::class, 'perf_rating_id')
                ->constrained('performance_ratings', 'perf_rating_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            
            $table->foreignIdFor(ProbationaryPerformance::class, 'probationary_performance_id')
                ->constrained('probationary_performances', 'probationary_performance_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        Schema::create('regular_performance_ratings', function (Blueprint $table) {
            $table->foreignIdFor(PerformanceCategory::class, 'perf_category_id')
                ->constrained('performance_categories', 'perf_category_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(PerformanceRating::class, 'perf_rating_id')
                ->constrained('performance_ratings', 'perf_rating_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(RegularPerformance::class, 'regular_performance_id')
                ->constrained('regular_performances', 'regular_performance_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('probationary_performance_ratings');
        Schema::dropIfExists('regular_performance_ratings');
        Schema::dropIfExists('regular_performances');
        Schema::dropIfExists('probationary_performances');
        Schema::dropIfExists('probationary_performance_periods');
        Schema::dropIfExists('regular_performance_periods');
        Schema::dropIfExists('performance_ratings');
        Schema::dropIfExists('performance_categories');
        Schema::enableForeignKeyConstraints();
    }
};

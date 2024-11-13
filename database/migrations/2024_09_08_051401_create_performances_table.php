<?php

use App\Models\Employee;
use App\Models\PerformanceCategory;
use App\Models\PerformanceDetail;
use App\Models\PerformancePeriod;
use App\Models\PerformanceRating;
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

        Schema::create('performance_periods', function (Blueprint $table) {
            $table->id('perf_period_id');
            $table->string('perf_period_name');
            $table->timestamps();
        });

        Schema::create('performance_details', function (Blueprint $table) {
            $table->id('perf_detail_id');

            $table->foreignIdFor(PerformancePeriod::class, 'perf_period_id')
                ->constrained('performance_periods', 'perf_period_id')
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

            $table->longText('supervisor_comments')->nullable();
            $table->timestamp('supervisor_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'supervisor')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('area_manager_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'area_manager')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('hr_manager_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'hr_manager')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // recommendation to make probationary become regular
            $table->boolean('is_final_recommend')->default(false);

            $table->boolean('is_employee_acknowledged')->default(false);
        });

        Schema::create('performance_category_ratings', function (Blueprint $table) {
            $table->id('perf_cat_rating_id');

            $table->foreignIdFor(PerformanceCategory::class, 'perf_category_id')
                ->constrained('performance_categories', 'perf_category_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(PerformanceRating::class, 'perf_rating_id')
                ->constrained('performance_ratings', 'perf_rating_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(PerformanceDetail::class, 'perf_detail_id')
                ->constrained('performance_details', 'perf_detail_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_categories');
        Schema::dropIfExists('performance_ratings');
        Schema::dropIfExists('performance_periods');
        Schema::dropIfExists('performance_details');
        Schema::dropIfExists('performance_category_ratings');
    }
};

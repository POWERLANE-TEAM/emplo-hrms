<?php

use App\Models\Employee;
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
        Schema::create('processes', function (Blueprint $table) {
            $table->id('process_id');
            $table->morphs('processable');

            $table->boolean('is_supervisor_approved')->default(false);
            $table->timestamp('supervisor_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'supervisor')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_area_manager_approved')->default(false);
            $table->timestamp('area_manager_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'area_manager')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_hr_manager_approved')->default(false);
            $table->timestamp('hr_manager_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'hr_manager')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};

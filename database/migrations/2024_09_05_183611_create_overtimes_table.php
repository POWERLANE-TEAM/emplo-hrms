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

            $table->foreignIdFor(Employee::class, 'ot_requestor')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('work_performed');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('hours_requested');
            $table->boolean('is_init_approved')->default(false);
            $table->timestamp('init_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'init_approved_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->boolean('is_final_approved')->default(false);
            $table->timestamp('final_approved_at')->nullable();

            $table->foreignIdFor(Employee::class, 'final_approved_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

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

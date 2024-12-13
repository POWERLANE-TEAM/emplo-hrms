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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id('overtime_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('work_performed');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('cut_off');
            $table->timestamp('filed_at');
            $table->timestamp('modified_at');
            $table->index(['filed_at', 'cut_off']);
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

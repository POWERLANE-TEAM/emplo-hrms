<?php

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll_summaries', function (Blueprint $table) {
            $table->id('payroll_summary_id');
            
            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Payroll::class, 'payroll_id')
                ->constrained('payrolls', 'payroll_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('reg_hrs', 5, 2)->nullable();              // regular hours worked
            $table->decimal('reg_nd', 5, 2)->nullable();               // regular night differential
            $table->decimal('reg_ot', 5, 2)->nullable();               // regular overtime
            $table->decimal('reg_ot_nd', 5, 2)->nullable();            // regular overtime night differential
            $table->decimal('rest_hrs', 5, 2)->nullable();             // rest day hours worked
            $table->decimal('rest_nd', 5, 2)->nullable();              // rest day night differential
            $table->decimal('rest_ot', 5, 2)->nullable();              // rest day overtime
            $table->decimal('rest_ot_nd', 5, 2)->nullable();           // rest day overtime night differential
            $table->decimal('reg_hol_hrs', 5, 2)->nullable();          // regular holiday hours worked
            $table->decimal('reg_hol_nd', 5, 2)->nullable();           // regular holiday night differential
            $table->decimal('reg_hol_ot', 5, 2)->nullable();           // regular holiday overtime
            $table->decimal('reg_hol_ot_nd', 5, 2)->nullable();        // regular holiday overtime night differential
            $table->decimal('reg_hol_rest_hrs', 5, 2)->nullable();     // regular holiday rest day hours worked
            $table->decimal('reg_hol_rest_nd', 5, 2)->nullable();      // regular holiday rest day night differential
            $table->decimal('reg_hol_rest_ot', 5, 2)->nullable();      // regular holiday rest day overtime
            $table->decimal('reg_hol_rest_ot_nd', 5, 2)->nullable();   // regular holiday rest day overtime night differential
            $table->decimal('spe_hol_hrs', 5, 2)->nullable();          // special holiday hours worked
            $table->decimal('spe_hol_nd', 5, 2)->nullable();           // special holiday night differential
            $table->decimal('spe_hol_ot', 5, 2)->nullable();           // special holiday overtime
            $table->decimal('spe_hol_ot_nd', 5, 2)->nullable();        // special holiday overtime night differential
            $table->decimal('spe_hol_rest_hrs', 5, 2)->nullable();     // special holiday rest day hours worked
            $table->decimal('spe_hol_rest_nd', 5, 2)->nullable();      // special holiday rest day night differential
            $table->decimal('spe_hol_rest_ot', 5, 2)->nullable();      // special holiday rest day overtime
            $table->decimal('spe_hol_rest_ot_nd', 5, 2)->nullable();   // special holiday rest day overtime night differential
            $table->unsignedTinyInteger('abs_days')->nullable();       // absence days
            $table->decimal('ut_hours', 5, 2)->nullable();             // undertime hours
            $table->decimal('td_hours', 5, 2)->nullable();             // tardiness hours
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_summaries');
    }
};

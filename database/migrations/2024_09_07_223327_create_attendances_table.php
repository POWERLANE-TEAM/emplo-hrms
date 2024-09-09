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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('time_in');
            $table->timestamp('time_out')->nullable();
        });


        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id('summary_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // cut-offs
            $table->integer('beg_date');    // beginning date
            $table->integer('end_date');    // end date    

            // worked hours will be stored into minutes instead
            $table->integer('reg_hours')->nullable();               // regular hours
            $table->integer('reg_nd_hours')->nullable();            // regular night diff
            $table->integer('reg_ot_hours')->nullable();            // regular overtime
            $table->integer('reg_ot_nd_hours')->nullable();         // regular overtime night diff
            $table->integer('rest_hours')->nullable();              // rest day hours
            $table->integer('rest_nd_hours')->nullable();           // rest day night diff
            $table->integer('rest_ot_hours')->nullable();           // rest day overtime
            $table->integer('rest_ot_nd_hours')->nullable();        // rest day overtime night diff
            $table->integer('reg_hol_hours')->nullable();           // regular holiday hours
            $table->integer('reg_hol_nd_hours')->nullable();        // regular holiday night diff
            $table->integer('reg_hol_ot_hours')->nullable();        // regular holiday overtime
            $table->integer('reg_hol_ot_nd_hours')->nullable();     // regular holiday overtime night diff
            $table->integer('spe_hol_hours')->nullable();           // special holiday hours
            $table->integer('spe_hol_nd_hours')->nullable();        // special holiday night diff
            $table->integer('spe_hol_ot_hours')->nullable();        // special holiday overtime
            $table->integer('spe_hol_ot_nd_hours')->nullable();     // special holiday overtime night diff
            $table->integer('absence_hours')->nullable();           // absences
            $table->integer('undertime_hours')->nullable();         // undertimes
            $table->integer('tardy_hours')->nullable();             // tardiness
            $table->timestamps();

            // this is fucking stressful
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendance_summaries');
    }
};

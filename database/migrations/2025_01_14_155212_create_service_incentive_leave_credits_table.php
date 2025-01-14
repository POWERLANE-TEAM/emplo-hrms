<?php

use App\Models\Employee;
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
        Schema::create('service_incentive_leave_credits', function (Blueprint $table) {
            $table->id('sil_credit_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
                
            $table->integer('vacation_leave_credits');
            $table->integer('sick_leave_credits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_incentive_leaves');
    }
};

<?php

use App\Models\Employee;
use App\Models\EmployeeComplaint;
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
        Schema::create('complaint_complainees', function (Blueprint $table) {
            $table->id('complaint_complainee_id');

            $table->foreignIdFor(EmployeeComplaint::class, 'emp_complaint_id')
                ->constrained('employee_complaints', 'emp_complaint_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'complainee')
                ->nullable()
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
        Schema::dropIfExists('complaint_complainees');
    }
};

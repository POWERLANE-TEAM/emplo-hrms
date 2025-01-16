<?php

use App\Models\Employee;
use App\Models\EmployeeDoc;
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
        Schema::create('coe_requests', function (Blueprint $table) {
            $table->id('coe_request_id');

            $table->foreignIdFor(Employee::class, 'requested_by')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'generated_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(EmployeeDoc::class, 'emp_coe_doc_id')
                ->nullable()
                ->constrained('employee_docs', 'emp_doc_id')
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
        Schema::dropIfExists('coe_requests');
    }
};

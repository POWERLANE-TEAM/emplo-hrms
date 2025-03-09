<?php

use App\Models\Employee;
use App\Models\EmployeeDoc;
use App\Models\ResignationStatus;
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

        Schema::create('resignation_statuses', function (Blueprint $table) {
            $table->smallIncrements('resignation_status_id');
            $table->string('resignation_status_name');
            $table->text('resignation_status_desc')->nullable();
        });

        Schema::create('resignations', function (Blueprint $table) {
            $table->id('resignation_id');

            $table->foreignIdFor(EmployeeDoc::class, 'emp_resignation_doc_id')
                ->constrained('employee_docs', 'emp_doc_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('initial_approver_signed_at')->nullable();

            $table->foreignIdFor(Employee::class, 'initial_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->text('initial_approver_comments')->nullable();

            $table->timestamp('retracted_at')->nullable();

            $table->foreignIdFor(ResignationStatus::class, 'resignation_status_id')
                ->nullable()
                ->constrained('resignation_statuses', 'resignation_status_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('filed_at')->useCurrent();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resignations');
        Schema::dropIfExists('resignation_statuses');
    }
};

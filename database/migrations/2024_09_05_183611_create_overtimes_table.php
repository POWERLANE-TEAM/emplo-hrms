<?php

use App\Models\OvertimePayrollApproval;
use App\Models\Payroll;
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
        Schema::create('overtime_payroll_approvals', function (Blueprint $table) {
            $table->id('payroll_approval_id');

            $table->foreignIdFor(Payroll::class, 'payroll_id')
                ->constrained('payrolls', 'payroll_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('initial_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'initial_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('secondary_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'secondary_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('third_approver_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'third_approver')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });


        Schema::create('overtimes', function (Blueprint $table) {
            $table->id('overtime_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(OvertimePayrollApproval::class, 'payroll_approval_id')
                ->constrained('overtime_payroll_approvals', 'payroll_approval_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('work_performed');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamp('authorizer_signed_at')->nullable();
            $table->foreignIdFor(Employee::class, 'authorizer')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('denied_at')->nullable();
            $table->foreignIdFor(Employee::class, 'denier')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('feedback')->nullable();

            $table->timestamp('filed_at');
            $table->timestamp('modified_at');
            $table->index(['filed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('overtimes');
        Schema::dropIfExists('overtime_payroll_approvals');
        Schema::enableForeignKeyConstraints();
    }
};

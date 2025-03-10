<?php

use App\Models\Barangay;
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
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id('emp_status_id');
            $table->string('emp_status_name');
            $table->longText('emp_status_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->longText('present_address');
            $table->foreignIdFor(Barangay::class, 'present_barangay')
                ->constrained('barangays', 'id')
                ->cascadeOnDelete();

            $table->longText('permanent_address');
            $table->foreignIdFor(Barangay::class, 'permanent_barangay')
                ->constrained('barangays', 'id')
                ->cascadeOnDelete();

            $table->string('contact_number', 15)->unique();
            $table->string('sex');
            $table->string('civil_status');
            $table->date('date_of_birth')->nullable();
            $table->string('sss_no', 10)->unique();
            $table->string('philhealth_no', 12)->unique();
            $table->string('tin_no', 12)->unique();
            $table->string('pag_ibig_no', 12)->unique();
            $table->binary('signature')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_docs', function (Blueprint $table) {
            $table->id('emp_doc_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_statuses');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('employee_docs');
    }
};

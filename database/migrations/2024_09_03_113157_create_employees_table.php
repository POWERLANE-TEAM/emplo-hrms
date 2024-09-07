<?php

use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\Position;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);

            $table->foreignIdFor(Position::class, 'position_id')
                ->constrained('positions', 'position_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Department::class, 'department_id')
                ->constrained('departments', 'department_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamp('hired_at');

            $table->foreignIdFor(EmploymentStatus::class, 'emp_status_id')
                ->constrained('employment_statuses', 'emp_status_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('present_address');
            $table->longText('permanent_address');
            $table->string('contact_number', 11);
            $table->string('photo')->nullable(); // emp photo file path
            $table->enum('sex', ['MALE', 'FEMALE']);
            $table->enum('civil_status', ['SINGLE', 'MARRIED', 'WIDOWED', 'LEGALLY SEPARATED']);
            $table->string('sss_no', 10);
            $table->string('philhealth_no', 12);
            $table->string('tin_no', 12);
            $table->string('pag_ibig_no', 12);
            $table->binary('signature');
            $table->string('education', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

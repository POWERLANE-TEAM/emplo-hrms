<?php

use App\Models\ComplaintConfidentiality;
use App\Models\ComplaintStatus;
use App\Models\ComplaintType;
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
        Schema::create('employee_complaints', function (Blueprint $table) {
            $table->id('emp_complaint_id');

            $table->foreignIdFor(Employee::class, 'complainant')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(ComplaintType::class, 'complaint_type_id')
                ->constrained('complaint_types', 'complaint_type_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(ComplaintConfidentiality::class, 'confidentiality_id')
                ->constrained('complaint_confidentialities', 'confidentiality_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->dateTime('occured_at');
            $table->longText('emp_complaint_desc');
            $table->string('supporting_info')->nullable();
            $table->longText('desired_resolution')->nullable();

            $table->foreignIdFor(ComplaintStatus::class, 'complaint_status_id')
                ->constrained('complaint_statuses', 'complaint_status_id')
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
        Schema::dropIfExists('employee_complaints');
    }
};

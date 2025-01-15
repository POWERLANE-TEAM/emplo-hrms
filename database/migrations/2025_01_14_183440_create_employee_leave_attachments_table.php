<?php

use App\Models\EmployeeLeave;
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
        Schema::create('employee_leave_attachments', function (Blueprint $table) {
            $table->id('attachment_id');

            $table->foreignIdFor(EmployeeLeave::class, 'emp_leave_id')
                ->constrained('employee_leaves', 'emp_leave_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('hashed_attachment');
            $table->string('attachment_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_attachments');
    }
};

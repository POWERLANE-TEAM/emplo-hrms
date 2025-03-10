<?php

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
        Schema::table('job_families', function (Blueprint $table) {
            $table->foreignIdFor(Employee::class, 'supervisor')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignIdFor(Employee::class, 'office_head')
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
        Schema::table('job_families', function (Blueprint $table) {
            $table->dropForeignIdFor(Employee::class, 'office_head');
            $table->dropTimestamps();
        });
    }
};

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
        Schema::table('specific_areas', function (Blueprint $table) {
            $table->foreignIdFor(Employee::class, 'area_manager')
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
        Schema::table('specific_areas', function (Blueprint $table) {
            $table->dropForeignIdFor(Employee::class, 'area_manager');
            $table->dropTimestamps();
        });
    }
};

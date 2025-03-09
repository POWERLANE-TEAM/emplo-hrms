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
        Schema::create('employee_experiences', function (Blueprint $table) {
            $table->id('employee_exp_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('experience_desc');
            $table->timestamps();
            $table->index(['experience_desc']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_experiences');
    }
};

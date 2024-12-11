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
        Schema::create('processes', function (Blueprint $table) {
            $table->id('process_id');
            $table->morphs('processable');

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

            $table->boolean('is_denied')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};

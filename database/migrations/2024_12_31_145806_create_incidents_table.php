<?php

use App\Models\Employee;
use App\Models\Issue;
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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id('incident_id');
            $table->longText('incident_description');
            $table->longText('resolution')->nullable();
            $table->timestamp('resolution_date')->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('priority');
            $table->timestamp('status_marked_at')->useCurrent();

            $table->foreignIdFor(Employee::class, 'status_marker')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'initiator')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'reporter')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Issue::class, 'originator')
                ->nullable()
                ->constrained('issues', 'issue_id')
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
        Schema::dropIfExists('incidents');
    }
};

<?php

use App\Models\Employee;
use App\Models\Incident;
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
        Schema::create('incident_record_collaborators', function (Blueprint $table) {
            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Incident::class, 'incident_id')
                ->constrained('incidents', 'incident_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_editor')->boolean(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_record_collaborators');
    }
};

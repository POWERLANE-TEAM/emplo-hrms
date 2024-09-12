<?php

use App\Models\Employee;
use App\Models\TrainingProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_providers', function (Blueprint $table) {
            $table->id('training_provider_id');
            $table->string('training_provider_name');
            $table->longText('training_provider_desc');
            $table->timestamps();
        });


        Schema::create('trainings', function (Blueprint $table) {
            $table->id('training_id');

            $table->foreignIdFor(Employee::class, 'trainee')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->dateTime('training_date');
            $table->string('training_title');
            $table->morphs('trainer');
            $table->longText('description')->nullable();
            $table->enum('completion_status', ['ONGOING', 'FINISHED']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('expiry_date')->nullable();
            $table->nullableMorphs('comment')->nullable();

            $table->foreignIdFor(Employee::class, 'prepared_by')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Employee::class, 'reviewed_by')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });


        Schema::create('outsourced_trainers', function (Blueprint $table) {
            $table->id('trainer_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);

            $table->foreignIdFor(TrainingProvider::class, 'training_provider')
                ->nullable()
                ->constrained('training_providers', 'training_provider_id')
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
        Schema::dropIfExists('training_providers');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('outsourced_trainers');
    }
};

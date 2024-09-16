<?php

use App\Models\Applicant;
use App\Models\Employee;
use App\Models\PreempRequirement;
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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id('applicant_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->timestamps();
        });

        
        Schema::create('applicant_docs', function (Blueprint $table) {
            $table->id('applicant_doc_id');

            $table->foreignIdFor(PreempRequirement::class, 'preemp_req_id')
                ->constrained('preemp_requirements', 'preemp_req_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Applicant::class, 'applicant_id')
                ->constrained('applicants', 'applicant_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->boolean('is_submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();

            $table->foreignIdFor(Employee::class, 'evaluated_by')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('applicant_docs');
    }
};

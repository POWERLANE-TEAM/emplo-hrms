<?php

use App\Models\Applicant;
use App\Models\Document;
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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id('applicant_id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->timestamp('applied_at');
            $table->dateTime('exam_date')->nullable();
            $table->dateTime('interview_date')->nullable();
            $table->timestamps();
        });

        Schema::create('applicant_docs', function (Blueprint $table) {
            $table->id('applicant_doc_id');

            $table->foreignIdFor(Document::class, 'document_id')
                ->constrained('documents', 'document_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Applicant::class, 'applicant_id')
                ->constrained('applicants', 'applicant_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean('is_submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();

            $table->foreignIdFor(Employee::class, 'received_by')
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

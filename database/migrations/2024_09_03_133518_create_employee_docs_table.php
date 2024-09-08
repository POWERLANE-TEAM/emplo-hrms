<?php

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
        Schema::create('employee_docs', function (Blueprint $table) {
            $table->id('emp_doc_id');

            $table->foreignIdFor(Employee::class, 'employee_id')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Document::class, 'document_id')
                ->nullable()
                ->constrained('documents', 'document_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('submitted_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_docs');
    }
};

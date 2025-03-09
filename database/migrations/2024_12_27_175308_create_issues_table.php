<?php

use App\Enums\IssueStatus;
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
        Schema::create('issues', function (Blueprint $table) {
            $table->id('issue_id');

            $table->foreignIdFor(Employee::class, 'issue_reporter')
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('confidentiality');

            $table->timestamp('occured_at')->nullable();
            $table->longText('issue_description');
            $table->longText('desired_resolution')->nullable();
            $table->unsignedTinyInteger('status')->default(IssueStatus::OPEN);
            $table->timestamp('status_marked_at');

            $table->foreignIdFor(Employee::class, 'status_marker')
                ->nullable()
                ->constrained('employees', 'employee_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->longText('given_resolution')->nullable();
            $table->timestamp('filed_at');
            $table->timestamp('modified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};

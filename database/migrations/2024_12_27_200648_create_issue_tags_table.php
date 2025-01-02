<?php

use App\Models\Issue;
use App\Models\IssueType;
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
        Schema::create('issue_tags', function (Blueprint $table) {
            $table->foreignIdFor(Issue::class, 'issue_id')
                ->constrained('issues', 'issue_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(IssueType::class, 'issue_type_id')
                ->constrained('issue_types', 'issue_type_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_tags');
    }
};

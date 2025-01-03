<?php

use App\Models\Incident;
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
        Schema::create('incident_tags', function (Blueprint $table) {
            $table->foreignIdFor(Incident::class, 'incident_id')
                ->constrained('incidents', 'incident_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // I need to utilize the issue type so it won't be a fucking pain in the ass
            // when an issue report has to be converted into an incident.
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
        Schema::dropIfExists('incident_tags');
    }
};

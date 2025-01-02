<?php

use App\Models\Incident;
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
        Schema::create('incident_attachments', function (Blueprint $table) {
            $table->id('attachment_id');
            $table->string('attachment');
            $table->string('attachment_name');

            $table->foreignIdFor(Incident::class, 'incident_id')
                ->constrained('incidents', 'incident_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_attachments');
    }
};

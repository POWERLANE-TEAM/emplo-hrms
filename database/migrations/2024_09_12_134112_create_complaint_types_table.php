<?php

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
        Schema::create('complaint_types', function (Blueprint $table) {
            $table->id('complaint_type_id');
            $table->string('complaint_type_name');
            $table->longText('complain_type_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('complaint_confidentialities', function (Blueprint $table) {
            $table->id('confidentiality_id');
            $table->string('confidentiality_pref');
            $table->longText('confidentiality_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('complaint_statuses', function (Blueprint $table) {
            $table->id('complaint_status_id');
            $table->string('complaint_status_name');
            $table->longText('complaint_status_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_types');
        Schema::dropIfExists('complaint_confidentialities');
        Schema::dropIfExists('complaint_statuses');
    }
};

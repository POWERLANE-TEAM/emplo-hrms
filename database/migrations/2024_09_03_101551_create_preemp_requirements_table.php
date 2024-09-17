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
        Schema::create('preemp_requirements', function (Blueprint $table) {
            $table->id('preemp_req_id');
            $table->string('preemp_req_name', 100);
            $table->longText('preemp_req_desc')->nullable();
            $table->string('sample_file')->nullable(); // file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preemp_requirements');
    }
};

<?php

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
        Schema::create('regions', function (Blueprint $table) {
            $table->string('region_code', 10)->primary();
            $table->string('region_name');
        });


        Schema::create('provinces', function (Blueprint $table) {
            $table->string('province_code', 10)->primary();
            $table->string('province_name');
            $table->string('region_code', 10)->index()->nullable();
        });


        Schema::create('cities', function (Blueprint $table) {
            $table->string('city_code', 10)->primary();
            $table->string('city_name');
            $table->string('province_code', 10)->index()->nullable();
        });


        Schema::create('barangays', function (Blueprint $table) {
            $table->string('barangay_code', 10)->primary();
            $table->string('barangay_name');
            $table->string('city_code', 10)->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('barangays');
    }
};

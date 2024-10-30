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
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('region_code')->index();
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('province_code');
            $table->string('region_code');
            $table->index(['province_code', 'region_code']);
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('city_code');
            $table->string('province_code');
            $table->string('region_code');
            $table->index(['city_code', 'province_code', 'region_code']);
        });

        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('city_code');
            $table->string('province_code');
            $table->string('region_code');
            $table->index(['city_code', 'province_code', 'region_code']);
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

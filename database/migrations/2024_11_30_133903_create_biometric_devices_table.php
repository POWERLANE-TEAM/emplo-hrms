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
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_name');
            $table->string('device_version');
            $table->string('device_os_version');
            $table->string('device_serial_number');
            $table->string('device_platform');
            $table->string('device_ssr');
            $table->string('device_fm_version');
            $table->string('device_pin_width');
            $table->string('device_work_code');
            $table->string('device_ip_address', 45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometric_devices');
    }
};

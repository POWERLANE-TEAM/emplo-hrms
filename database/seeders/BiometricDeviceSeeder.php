<?php

namespace Database\Seeders;

use App\Http\Helpers\BiometricDevice as Device;
use App\Models\BiometricDevice;
use Illuminate\Database\Seeder;

class BiometricDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zk = new Device('169.254.255.255');

        BiometricDevice::updateOrCreate([
            'device_name' => $zk->getDeviceName(),
            'device_version' => $zk->getDeviceVersion(),
            'device_os_version' => $zk->getDeviceOsVersion(),
            'device_serial_number' => $zk->getDeviceSerialNumber(),
            'device_platform' => $zk->getDevicePlatform(),
            'device_ssr' => $zk->getDeviceSsr(),
            'device_fm_version' => $zk->getDeviceFmVersion(),
            'device_pin_width' => $zk->getDevicePinWidth(),
            'device_work_code' => $zk->getDeviceWorkCode(),
            'device_ip_address' => $zk->getIp(),
        ]);
    }
}

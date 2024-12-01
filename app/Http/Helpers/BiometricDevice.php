<?php

declare(strict_types=1);

namespace App\Http\Helpers;

use App\Models\BiometricDevice as Device;
use Illuminate\Support\Str;
use Rats\Zkteco\Lib\ZKTeco;

class BiometricDevice 
{
    private string $deviceName = '';

    private string $deviceVersion = '';

    private string $deviceOsVersion = '';

    private string $deviceSerialNumber = '';

    private string $devicePlatform = '';

    private string $deviceSsr = '';

    private string $deviceFmVersion = '';

    private string $devicePinWidth = '';

    private string $deviceFaceFunction = '';

    private string $ip = '';

    private ZKTeco $zk;

    private $device;

    public function __construct(?string $ip = null)
    {
        $this->device = Device::first();
        $this->ip = $this->device->device_ip_address ?? $ip;

        $this->zk = new ZKTeco($this->ip);
        $this->zk->connect();
        $this->zk->disableDevice();
    }

    /**
     * Set ip address to use.
     * 
     * @param mixed $ip
     * @return void
     */
    public function setIp(?string $ip)
    {
        $this->device->save($ip);
        $this->ip = $ip;
        $this->zk->disconnect();
        $this->zk = new ZKTeco($this->ip);
        $this->zk->connect();
        $this->zk->disableDevice();
    }

    /**
     * @return mixed|string|\Illuminate\Config\Repository|null
     */
    public function getIp()
    {
        return $this->ip;
    }

    public function getDeviceName()
    {
        return $this->formatString($this->zk->deviceName());
    }

    public function getDeviceVersion()
    {
        return $this->formatString($this->zk->version());
    }

    public function getDeviceOsVersion()
    {
        return $this->formatString($this->zk->osVersion());
    }

    public function getDeviceSerialNumber()
    {
        return $this->formatString($this->zk->serialNumber());
    }

    public function getDevicePlatform()
    {
        return $this->formatString($this->zk->platform());
    }

    public function getDeviceSsr()
    {
        return $this->formatString($this->zk->ssr());
    }

    public function getDeviceFmVersion()
    {
        return $this->formatString($this->zk->fmVersion());
    }

    public function getDevicePinWidth()
    {
        return $this->formatString($this->zk->pinWidth());
    }

    public function getDeviceFaceFunction()
    {
        return $this->formatString($this->zk->faceFunctionOn());
    }

    public function getDeviceWorkCode()
    {
        return $this->formatString($this->zk->workCode());
    }

    /**
     * Return every possible information about the device specification.
     * 
     * @return object
     */
    public function getDeviceInfo()
    {
        $specs = [
            'name' => $this->zk->deviceName(),
            'version' => $this->zk->version(),
            'osVersion' => $this->zk->osVersion(),
            'serialNumber' => $this->zk->serialNumber(),
            'platform' => $this->zk->platform(),
            'ssr' => $this->zk->ssr(),
            'fmVersion' => $this->zk->fmVersion(),
            'pinWidth' => $this->zk->pinWidth(),
            'workCode' => $this->zk->workCode(),
            'faceFunction' => $this->zk->faceFunctionOn(),
        ];

        return (object) array_map(function (string $item) {
            return $this->formatString($item);
        }, $specs);
    }

    public function getRawAttendanceLogs()
    {
        return collect($this->zk->getAttendance())
            ->map(function ($item) {
                return (object) $item;
            });
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUsers()
    {
        return collect($this->zk->getUser())
            ->map(function ($item) {
                return (object) $item;
            });
    }

    public function setToSleep()
    {
        $this->zk->sleep();
    }

    public function setToResume()
    {
        $this->zk->resume();
    }

    public function setClearLCD()
    {
        $this->zk->clearLCD();
    }

    /**
     * Do not use.
     * 
     * @param int $uid
     * @return \Illuminate\Support\Collection
     */
    public function getFingerprint(int $uid)
    {
        return collect($this->zk->getFingerprint($uid))
            ->map(function ($item) {
                return (object) $item;
            });
    }

    /**
     * Power off the device.
     * 
     * @return void
     */
    public function shutdown()
    {
        $this->zk->shutdown();
    }

    /**
     * Restart the device.
     * 
     * @return void
     */
    public function restart()
    {
        $this->zk->restart();
    }

    /**
     * Says "Thank you"
     * 
     * @return void
     */
    public function testVoice()
    {
        $this->zk->testVoice();
    }

    public function __destruct()
    {
        $this->zk->enableDevice();
    }

    private function formatString(string ...$params)
    {
        if (count($params) > 1) {
            $formattedStrings = [];
            
            foreach ($params as $param) {
                $formattedStrings = Str::of($param)->chopStart('~')->trim()->toString();
            }
            return $formattedStrings;
        } else {
            return Str::of($params[0])->chopStart('~')->trim()->toString();
        }
    }

    protected function getCleanedAttendanceLogs()
    {
        //
    }
}
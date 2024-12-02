<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Enums\UserPermission;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\BiometricDevice;

class ManageBiometricDevice extends Component
{
    public $ipAddress;

    private BiometricDevice $zkInstance;

    public function boot()
    {
        $this->zkInstance = new BiometricDevice();
    }

    public function updateDeviceIp()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::UPDATE_BIOMETRIC_ATTENDANCE_DEVICE_CONFIG)) {
            abort (403);
        }

        $this->zkInstance->setIp($this->ipAddress ?: __('Shouldn\'t be empty.'));
    }

    public function shutdownDevice()
    {
        $this->zkInstance->shutdown();
    }

    public function restartDevice()
    {
        $this->zkInstance->restart();
    }

    public function testDeviceVoice()
    {
        $this->zkInstance->testVoice();
    }

    public function putDeviceToSleep()
    {
        $this->zkInstance->setToSleep();
    }

    public function resumeDeviceFromSleep()
    {
        $this->zkInstance->setToResume();
    }

    public function clearLCD()
    {
        $this->zkInstance->setClearLCD();
    }

    public function render()
    {
        return view('livewire.admin.manage-biometric-device', [
            'device' => $this->zkInstance->getDeviceInfo(),
        ]);
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Http\Helpers\BiometricDevice;

class ManageAttendanceLogs extends Component
{
    private BiometricDevice $zkInstance;

    public function boot()
    {
        $this->zkInstance = new BiometricDevice();
    }

    public function formatAttendanceLogs()
    {
        return $this->zkInstance->getAttendanceLogs()
            ->map(function ($item) {
                return (object) [
                    'uid' => $item->uid,
                    'id' => (int) $item->id,
                    'state' => $item->state,
                    'timestamp' => Carbon::parse($item->timestamp)->format('g:i A'),
                    'type' => $item->type,
                ];
            });
    }

    public function clearAttendanceLogs()
    {
        //
    }

    public function getUsers()
    {
        return $this->zkInstance->getUsers();
    }


    public function render()
    {
        dump($this->zkInstance->getAttendanceLogs());
        return view('livewire.admin.manage-attendance-logs', [
            'attendanceLogs' => $this->formatAttendanceLogs(),
        ]);
    }
}

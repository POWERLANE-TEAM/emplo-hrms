<?php

namespace App\Livewire\Employee\Dashboard;

use Livewire\Component;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Enums\BiometricPunchType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DailyTimeRecord extends Component
{
    private function getDtr()
    {
        $dtr = AttendanceLog::whereDate('timestamp', today())
            ->where('employee_id', Auth::user()->account->employee_id)
            ->get();

        if ($dtr->isEmpty()) {
            return (object) [
                'checkIn' => null,
                'checkOut' => null,
            ];            
        }    

        $type = $this->getPunchesTime($dtr);

        return (object) [
            'check_in' => $type->checkIn,
            'check_out' => $type->checkOut,
        ];            
    }

    private function getPunchesTime(Collection $group)
    {
        return $group->reduce(function ($carry, $log) {
            $time = Carbon::parse($log->timestamp)->format('g:i A');

            if (in_array($log->type, [
                BiometricPunchType::OVERTIME_IN->value,
                BiometricPunchType::OVERTIME_OUT->value,
            ])) {
                return $carry;
            }    

            match ($log->type) {
                BiometricPunchType::CHECK_IN->value => $carry->checkIn = $time,
                BiometricPunchType::CHECK_OUT->value => $carry->checkOut = $time,
            };

            return $carry;
        }, (object) [
            'checkIn' => null,
            'checkOut' => null,
        ]);
    }


    public function render()
    {
        // dd($this->getDtr());
        return view('livewire.employee.dashboard.daily-time-record', [
            'dtr' => $this->getDtr(),
        ]);
    }
}

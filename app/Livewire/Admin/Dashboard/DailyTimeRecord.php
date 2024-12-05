<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Enums\BiometricPunchType;
use Illuminate\Support\Collection;
use App\Http\Helpers\BiometricDevice;
use App\Actions\GenerateRandomUserAvatar;

class DailyTimeRecord extends Component
{
    #[Locked]
    public $dateToday;

    private BiometricDevice $zkInstance;

    public function mount()
    {
        $this->dateToday = Carbon::today();
    }

    public function boot()
    {
        $this->zkInstance = new BiometricDevice();
    }

    private function getTodayDtr()
    {   
        $this->upsertTodayDtr();

        return AttendanceLog::whereDate('timestamp', $this->dateToday)
            ->with('employee')
            // ->orderByDesc('timestamp')
            ->get()
            ->groupBy('employee_id')
            ->map(function ($group) {
                $type = $this->getPunchesTime($group);
                $punch = $group->first();
                $employee = $punch->employee;

                return (object) [
                    'uid' => $punch->uid,
                    'state' => $punch->state,
                    'checkIn' => $type->checkIn,
                    'checkOut' => $type->checkOut,
                    'overtimeIn' => $type->overtimeIn,
                    'overtimeOut' => $type->overtimeOut,
                    'employee' => optional($employee, function ($puncher) {
                        return (object) [
                            'id' => $puncher->employee_id,
                            'name' => $puncher->full_name,
                            'photo' => $puncher->photo ?? app(GenerateRandomUserAvatar::class)($puncher->full_name),
                        ];
                    }),
                ];
            });
    }

    private function upsertTodayDtr()
    {
        $this->zkInstance->getRawAttendanceLogs()
            ->filter(fn ($log) => Carbon::parse($log->timestamp)->isSameDay($this->dateToday))
            ->each(function ($item) {
                AttendanceLog::upsert([
                    'uid' => $item->uid,
                    'employee_id' => (int) $item->id,
                    'state' => $item->state,
                    'type' => $item->type,
                    'timestamp' => $item->timestamp
                ],
                uniqueBy: ['uid'], 
                update: [
                    'employee_id',
                    'state',
                    'timestamp',
                    'type',
                ]);
            });
    }

    private function getPunchesTime(Collection $group)
    {
        return $group->reduce(function ($carry, $log) {
            $time = Carbon::parse($log->timestamp)->format('g:i A');

            match ($log->type) {
                BiometricPunchType::CHECK_IN->value => $carry->checkIn = $time,
                BiometricPunchType::CHECK_OUT->value => $carry->checkOut = $time,
                BiometricPunchType::OVERTIME_IN->value => $carry->overtimeIn = $time,
                BiometricPunchType::OVERTIME_OUT->value => $carry->overtimeOut = $time,
            };

            return $carry;
        }, (object) [
            'checkIn' => null,
            'checkOut' => null,
            'overtimeIn' => null,
            'overtimeOut' => null,
        ]);
    }

    public function render()
    {
        $dtrLogs = $this->getTodayDtr();
        $totalDtr = $dtrLogs->count();
        
        return view('livewire.admin.dashboard.daily-time-record', [
            'dtrDate' => $this->dateToday->format('F, d Y')
        ], compact('dtrLogs', 'totalDtr'));
    }
}

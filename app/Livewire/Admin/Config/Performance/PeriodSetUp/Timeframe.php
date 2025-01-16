<?php

namespace App\Livewire\Admin\Config\Performance\PeriodSetup;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\RegularPerformancePeriod;

class Timeframe extends Component
{
    public $interval;

    public function save()
    {
        $latest = RegularPerformancePeriod::latest()->first();

        $latestPeriod = Carbon::parse($latest->end_date);

        if ($this->interval === '2') {
            $latestPeriod = $latestPeriod->copy()->addWeek()->toDateTimeString();
        } elseif ($this->interval === '3') {
            $latestPeriod = $latestPeriod->copy()->addWeek()->toDateTimeString();
        }

        $latest->end_date = $latestPeriod;
        $latest->save();

        $this->reset();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Timeframe changed successfully.',
        ]);

        $this->dispatch('changes-saved');
    }

    public function render()
    {
        return view('livewire.admin.config.performance.period-setup.timeframe');
    }
}

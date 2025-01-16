<?php

namespace App\Livewire\Admin\Config\Performance\PeriodSetUp;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\RegularPerformancePeriod;
use App\Enums\PerformanceEvaluationPeriod;

class Start extends Component
{
    public $period;

    public function save()
    {   
        $this->authorize('openRegularsEvaluationPeriod');

        RegularPerformancePeriod::create([
            'period_name' => PerformanceEvaluationPeriod::ANNUAL->value,
            'start_date' => $this->period,
            'end_date' => Carbon::parse($this->period)->addWeek(),
        ]);

        $this->reset();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Start Date changed successfully.',
        ]);

        $this->dispatch('changes-saved');
    }

    public function render()
    {
        return view('livewire.admin.config.performance.period-set-up.start');
    }
}

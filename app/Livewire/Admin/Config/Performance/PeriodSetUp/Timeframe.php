<?php

namespace App\Livewire\Admin\Config\Performance\PeriodSetup;

use Livewire\Component;

class Timeframe extends Component
{
    public function save()
    {
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

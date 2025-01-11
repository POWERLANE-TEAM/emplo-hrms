<?php

namespace App\Livewire\Admin\Config\Performance\PeriodSetUp;

use Livewire\Component;

class Start extends Component
{
    public function save()
    {
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

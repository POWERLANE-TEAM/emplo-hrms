<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class CalendarEvents extends Component
{
    public function render(): View
    {
        $holidays = Holiday::query()
            ->whereBetween('date', [
                Carbon::tomorrow()->format('m-d'),
                today()->addWeek()->format('m-d'),
            ])
            ->orderBy('date')
            ->limit(2)
            ->get();

        return view('livewire.admin.dashboard.calendar-events', compact('holidays'));
    }
}

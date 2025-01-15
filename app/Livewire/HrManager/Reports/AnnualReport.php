<?php

namespace App\Livewire\HrManager\Reports;

use App\Models\Holiday;
use App\Models\Payroll;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;

class AnnualReport extends Component
{
    public $year;

    #[Locked]
    public $holidays;

    public function mount()
    {
        $this->holidays = Holiday::all();

        $this->year = $this->availableYears->filter(function ($year) {
            return $year < now()->year;
        })->last();
    }

    #[Computed]
    public function availableYears()
    {
        return Payroll::selectRaw('EXTRACT(YEAR FROM cut_off_start) as year')
            ->union(Payroll::selectRaw('EXTRACT(YEAR FROM cut_off_end) as year'))
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->mapWithKeys(function ($year) {
                return [$year => $year];
            });
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.annual-report');
    }
}

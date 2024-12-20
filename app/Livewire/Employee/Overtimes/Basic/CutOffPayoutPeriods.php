<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Models\Payroll;
use Livewire\Component;
use App\Models\Employee;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;

class CutOffPayOutPeriods extends Component
{
    public ?Employee $employee;

    #[Locked] 
    public $payroll; 

    #[Locked]
    public $totalOtHours = 0;

    private function getTotalOtHours()
    {
        $payroll = Payroll::with([
            'overtimeSummaries.overtimes' => function ($query) {
                $query->select('overtime_id', 'start_time', 'end_time');
            }
        ])->find($this->payroll);

        $totalSeconds = $payroll->overtimeSummaries->flatMap(function ($summary) {
            return $summary->overtimes->map(function ($ot) {
                $start = Carbon::parse($ot->start_time);
                $end = Carbon::parse($ot->end_time);

                return $start->diffInSeconds($end);
            });
        })->sum();
        
        $totalHours = $totalSeconds / 3600;
        return $totalHours;
    }

    public function render()
    {
        // $totalSeconds       = $this->getTotalOtHours();
        // $interval           = CarbonInterval::seconds($totalSeconds);
        // $this->totalOtHours = $interval->cascade()->forHumans();

        return view('livewire.employee.overtimes.basic.cut-off-payout-periods');
    }
}

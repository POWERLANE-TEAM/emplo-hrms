<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Http\Helpers\Payroll;
use Livewire\Component;

class CutOffPayoutPeriods extends Component
{
    public function render()
    {
        $start = Payroll::getCutOffPeriod()['start']->format('F d, Y');
        $end = Payroll::getCutOffPeriod()['end']->format('F d, Y');
        $payout = Payroll::getPayoutDate()->format('F d, Y');

        return view('livewire.employee.overtimes.basic.cut-off-payout-periods', 
            compact('start', 'end', 'payout')
        );
    }
}

<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Http\Helpers\Payroll;
use Livewire\Component;

class CutOffPayoutPeriods extends Component
{
    public function render()
    {
        $cutOff = Payroll::getCutOffPeriod(isReadableFormat: true);
        $cutOff = $cutOff['start'].' - '.$cutOff['end'];
        $payout = Payroll::getPayoutDate(isReadableFormat: true);

        return view('livewire.employee.overtimes.basic.cut-off-payout-periods', 
            compact('cutOff', 'payout')
        );
    }
}

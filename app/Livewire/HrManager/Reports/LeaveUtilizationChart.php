<?php

namespace App\Livewire\HrManager\Reports;

use Livewire\Component;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Cache;

class LeaveUtilizationChart extends Component
{
    /*
     * BACK-END REPLACE / REQUIREMENTS:
     * 
     * ONLY FETCH ROWS FROM SELECTED YEAR.
     * 
     * FETCH FROM DATABASE:
     * 
     * 1. Fetch leave types.
     * 
     * 2. 'total': Total count of ALL ENTITLED leave days across ALL leave types.
     * 3. 'total': Total count of ALL REMAINING leave days across ALL leave types.
     *     --> Put this in the 'all' array.
     * 
     * 3. 'total': Total count of ENTITLED leave days for EACH LEAVE TYPE.
     * 4. 'used': Total count of entitled leave days for EACH LEAVE TYPE.
     * 
     * ADDITIONAL NOTES
     * ► This just needs fetching from the database. The logic is already implemented.
     * ► After fetching, replace the leaveData array in mount() function.
     * 
     */

    public $year;

    #[Locked]
    public $leaveData;

    public function mount()
    {
        $key = sprintf(config('cache.keys.reports.leave_utilization_rate'), $this->year);

        $this->leaveData = Cache::get($key);

        if ($this->leaveData) return;

        $employees = Employee::activeEmploymentStatus()->get();

        $silCredits = $employees->sum(fn ($employee) => $employee->actual_sil_credits);

        $totalSilCredits = $silCredits * 2;
        
        $usedVacationCredits = EmployeeLeave::whereHas('category', function ($query) {
            $query->where('leave_category_name', 'Vacation Leave');
        })
            ->whereYear('filed_at', $this->year)
            ->whereYear('fourth_approver_signed_at', $this->year)
            ->get()
            ->count();

        $usedSickCredits = EmployeeLeave::whereHas('category', function ($query) {
            $query->where('leave_category_name', 'Sick Leave');
        })
            ->whereYear('filed_at', $this->year)
            ->whereYear('fourth_approver_signed_at', $this->year)
            ->get()
            ->count();

        $totalUsedSilCredits = $usedVacationCredits + $usedSickCredits;

        $this->leaveData = [
            'all' => [
                'used' => $totalUsedSilCredits, 
                'total' => $totalSilCredits
            ],
            'sick' => [
                'used' => $usedSickCredits, 
                'total' => $totalSilCredits
            ],
            'vacation' => [
                'used' => $usedVacationCredits, 
                'total' => $totalSilCredits
            ],
        ];

        Cache::forever($key, $this->leaveData);
    }

    public function render()
    {
        return view('livewire.hr-manager.reports.leave-utilization-chart');
    }
}

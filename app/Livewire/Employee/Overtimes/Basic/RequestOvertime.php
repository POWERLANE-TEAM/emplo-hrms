<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use Closure;
use App\Enums\Payroll;
use Livewire\Component;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\OvertimePayrollApproval;
use App\Models\Payroll as PayrollModel;

class RequestOvertime extends Component
{
    public $state = [
        'workToPerform' => '',
        'startTime' => null,
        'endTime' => null,
    ];

    #[Locked]
    public $hoursRequested = 0;

    public function mount()
    {
        $this->state['startTime'] = now()->format('Y-m-d H:i:s');
    }

    public function updated($prop)
    {
        if (
            in_array($prop, ['state.startTime', 'state.endTime']) &&
            isset($this->state['startTime'], $this->state['endTime'])
        ) {
            $start = Carbon::parse($this->state['startTime']);
            $end = Carbon::parse($this->state['endTime']);

            if ($end->gt($start->copy()->addHours(5))) {
                $this->addError('state.endTime', __('Overtime duration must be within 5 hours only.'));
            } elseif ($end->lessThan($start->copy()->addMinutes(30))) {
                $this->addError('state.endTime', __('Overtime duration must be at least 30 minutes.'));
            } else {
                $this->resetErrorBag('state.endTime');
                $this->hoursRequested = $start->diff($end)->format('%h hours and %i minutes');
            }
        }
    }

    public function save()
    {
        $this->authorize('submitOvertimeRequest', Auth::user());
        $this->authorize('submitOvertimeRequestToday', $this->employee);
        $this->authorize('submitNewOrAnotherOvertimeRequest', $this->employee);
        
        $this->validate();
            
        DB::transaction(function () {
            $payroll            = $this->getOrStoreNewPayrollRecord();
            $payrollApproval    = $this->storePayrollApproval($payroll);
            $this->storeOvertimeRequest($payrollApproval);
        });
    
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('refreshDatatable');
        $this->dispatch('changesSaved');
    }

    #[On('resetOvertimeRequestModal')]
    public function discard()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    private function getCutoffAndPayout(): array
    {
        $requestedDate = $this->state['startTime'];

        return Payroll::getCutOffPeriod($requestedDate);
    }

    private function getOrStoreNewPayrollRecord(): PayrollModel
    {
        $cutoffAndPayout = $this->getCutoffAndPayout();

        return PayrollModel::firstOrCreate([
            'cut_off_start' => $cutoffAndPayout['start']->toDateString(),
            'cut_off_end'   => $cutoffAndPayout['end']->toDateString(),
            'payout'        => Payroll::getPayoutDate($this->state['startTime'])->toDateString(),
        ]);
    }

    private function storePayrollApproval(PayrollModel $payroll): OvertimePayrollApproval
    {
        $existing = OvertimePayrollApproval::where('payroll_id', $payroll->payroll_id)
            ->whereHas('overtimes', function ($query) {
                $query->where('employee_id', $this->employee->employee_id);
            })->first();

        return $existing ?? $payroll->overtimeApprovals()->create([
            'payroll_id' => $payroll->payroll_id,
        ]);
    }

    private function storeOvertimeRequest(OvertimePayrollApproval $payrollApproval): Overtime
    {
        return $payrollApproval->overtimes()->create([
            'employee_id'           => $this->employee->employee_id,
            'payroll_approval_id'   => $payrollApproval->payroll_approval_id,
            'work_performed'        => $this->state['workToPerform'],
            'start_time'            => $this->state['startTime'],
            'end_time'              => $this->state['endTime'],
        ]);
    }

    #[Computed]
    public function currentPayroll()
    {
        return PayrollModel::latest('cut_off_start')->first();
    }

    #[Computed]
    private function employee()
    {
        return Auth::user()->account;
    }

    public function rules(): array
    {
        return [
            'state.workToPerform'   => 'required|string|max:100',
            'state.startTime'       => 'required|date|after_or_equal:today',
            'state.endTime'         => [
                'required',
                'date',
                'after:state.startTime',
                function (string $attibute, mixed $value, Closure $fail) {
                    $startTime = Carbon::parse(request('state.startTime'));
                    $endTime = Carbon::parse($value);

                    if ($startTime->lt($this->currentPayroll->cut_off_start) ||
                        $endTime->gt($this->currentPayroll->cut_off_end)) {
                        $fail(__('Start or end date and time must be within the current payroll period.'));
                    } elseif ($endTime->lessThan($startTime->copy()->addMinutes(30))) {
                        $fail(__('End time must be at least 30 minutes from start time.'));
                    } elseif ($endTime->gt($startTime->copy()->addHours(5))) {
                        $fail(__('End time must be not more than 5 hours from start time.'));
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'state.workToPerform.required'      => __('Please provide a description.'),
            'state.workToPerform.string'        => __('Description must be a valid string.'),
            'state.workToPerform.max'           => __('Description must not exceed 100 characters.'),
            'state.startTime.required'          => __('Please provide a start time.'),
            'state.startTime.after_or_equal'    => __('Starting date must be today or in the future.'),
            'state.endTime.required'            => __('Please provide an end time.'),
            'state.endTime.after'               => __('The end time must be after the start time.'),
        ];
    }

    public function render()
    {
        return view('livewire.employee.overtimes.basic.request-overtime');
    }
}

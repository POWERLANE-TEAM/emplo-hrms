<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Enums\Payroll;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\OvertimePayrollApproval;
use App\Models\Payroll as PayrollModel;
use App\Livewire\Employee\Tables\Basic\RecentOvertimesTable;
use App\Livewire\Employee\Tables\Basic\ArchiveOvertimesTable;

class EditOvertimeRequest extends Component
{
    public $state = [
        'workToPerform' => '',
        'date' => null,
        'startTime' => null,
        'endTime' => null,
    ];

    /**
     * Set attributes in readable format.
     */
    #[Locked]
    public $overtime;

    public ?Overtime $request = null;

    #[Locked]
    public $hoursRequested = 0;

    #[Locked]
    public $editMode = false;

    public function update()
    {
        if ($this->editMode) {

            if (! $this->request) {
                abort (403);
            }

            $this->authorize('updateOvertimeRequest', [$this->employee, $this->request]);

            $this->validate();
            
            DB::transaction(function () {
                $payroll                    = $this->getOrStoreNewPayrollRecord();
                $payrollApproval            = $this->getOrStorePayrollApproval($payroll);
                $previousPayrollApprovalId  = $this->request->payroll_approval_id;

                $this->updateOvertimeRequest($payrollApproval);
                $this->removeUnusedApproval($previousPayrollApprovalId);    
            });
        }
        
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved')->to(ArchiveOvertimesTable::class);
        $this->dispatch('changesSaved')->to(RecentOvertimesTable::class);
        $this->dispatch('changesSaved');
    }

    private function updateOvertimeRequest(OvertimePayrollApproval $payrollApproval): void
    {
        $this->request->update([
            'payroll_approval_id'   => $payrollApproval->payroll_approval_id,
            'work_performed'        => $this->state['workToPerform'],
            'date'                  => $this->state['date'],
            'start_time'            => $this->state['startTime'],
            'end_time'              => $this->state['endTime'],
        ]);
    }

    private function removeUnusedApproval(int $previousPayrollApprovalId)
    {
        $previousPayrollApproval = OvertimePayrollApproval::find($previousPayrollApprovalId);

        if ($previousPayrollApproval && ! $previousPayrollApproval->overtimes()->exists()) {
            $previousPayrollApproval->delete();
        }
    }

    private function getOrStorePayrollApproval(PayrollModel $payroll): OvertimePayrollApproval
    {
        $existing = OvertimePayrollApproval::where('payroll_id', $payroll->payroll_id)
            ->whereHas('overtimes', function ($query) {
                $query->where('employee_id', $this->employee->employee_id);
            })->first();

        return $existing ?? $payroll->overtimeApprovals()->create([
            'payroll_id' => $payroll->payroll_id,
        ]);
    }

    private function getCutoffAndPayout(): array
    {
        $requestedDate = $this->state['date'];

        return Payroll::getCutOffPeriod($requestedDate);
    }

    private function getOrStoreNewPayrollRecord(): PayrollModel
    {
        $cutoffAndPayout = $this->getCutoffAndPayout();

        return PayrollModel::firstOrCreate([
            'cut_off_start' => $cutoffAndPayout['start']->toDateString(),
            'cut_off_end'   => $cutoffAndPayout['end']->toDateString(),
            'payout'        => Payroll::getPayoutDate($this->state['date'])->toDateString(),
        ]);
    }


    #[On('showOvertimeRequest')]
    public function openEditMode(int $overtimeId, bool $isEditable = false)
    {
        $this->request = Overtime::with(['payrollApproval'])->find($overtimeId);

        if (! $this->request) {
            $this->dispatch('modelNotFound', [
                'feedbackMsg' => __('Sorry, it seems like this record has been removed.'),
            ]);
        } else {
            $this->state = [
                'overtimeId'    => $this->request->overtime_id,
                'workToPerform' => $this->request->work_performed,
                'date'          => Carbon::parse($this->request->date)->format('Y-m-d'),
                'startTime'     => Carbon::parse($this->request->start_time)->format('H:i'),
                'endTime'       => Carbon::parse($this->request->end_time)->format('H:i'),
            ];
            $this->hoursRequested = $this->request->hoursRequested;
            
            $this->makeKeysReadable();

            if ($isEditable) {
                if (Gate::allows('editOvertimeRequest', $this->request)) {
                    $this->editMode = true;
                }
            }

            $this->dispatch('openOvertimeRequestModal');
        }
    }

    #[On('resetOvertimeRequestModal')]
    public function resetEditMode()
    {
        $this->reset('editMode');
    }

    private function makeKeysReadable()
    {
        $this->overtime = (object) [
            'filedAt'        =>  $this->request->filed_at,
            'authorizedBy'   =>  $this->checkIfSameAsAuthUser($this->request->authorizedBy),
            'authorizedAt'   =>  $this->request->authorizer_signed_at,
            'deniedBy'       =>  $this->request->deniedBy?->full_name,
            'deniedAt'       =>  $this->request->denied_at,
            'feedback'       =>  $this->request->feedback,
        ];
    }

    private function checkIfSameAsAuthUser(?Employee $employee)
    {
        return $employee?->employee_id === Auth::user()->account->employee_id
            ? "{$employee?->full_name} (You)"
            : $employee?->full_name;
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
            'state.date'            => 'required|date|after_or_equal:today',
            'state.startTime'       => 'required|date_format:H:i',
            'state.endTime'         => 'required|date_format:H:i|after:state.startTime',
        ];
    }

    public function messages(): array
    {
        return [
            'state.workToPerform.required'  => __('Please provide a description.'),
            'state.workToPerform.string'    => __('Description must be a valid string.'),
            'state.workToPerform.max'       => __('Description must not exceed 100 characters.'),
            'state.date.required'           => __('Please provide a valid date.'),
            'state.date.date'               => __('The date must be a valid format.'),
            'state.date.after_or_equal'     => __('The date must not be in the past.'),
            'state.startTime.required'      => __('Please provide a start time.'),
            'state.startTime.date_format'   => __('The start time must be in the format HH:mm.'),
            'state.endTime.required'        => __('Please provide an end time.'),
            'state.endTime.date_format'     => __('The end time must be in the format HH:mm.'),
            'state.endTime.after'           => __('The end time must be after the start time.'),
        ];
    }

    public function render()
    {
        return view('livewire.employee.overtimes.basic.edit-overtime-request');
    }
}

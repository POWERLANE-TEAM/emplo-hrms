<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Enums\Payroll;
use Livewire\Component;
use App\Models\Overtime;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
                $this->request->update([
                    'employee_id' => $this->employee->employee_id,
                    'work_performed' => $this->state['workToPerform'],
                    'date' => $this->state['date'],
                    'start_time' => $this->state['startTime'],
                    'end_time' => $this->state['endTime'],
                    'cut_off' => $this->getCutOffType(),
                ]);
            });    
        }
        
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved')->to(ArchiveOvertimesTable::class);
        $this->dispatch('changesSaved')->to(RecentOvertimesTable::class);
        $this->dispatch('changesSaved');
    }

    #[On('showOvertimeRequest')]
    public function openEditMode(int $overtimeId)
    {
        $this->request = Overtime::with([
                'processes',
                'processes.initialApprover',
                'processes.secondaryApprover',
                'processes.deniedBy',
            ])->find($overtimeId);

        if (! $this->request) {
            $this->dispatch('modelNotFound', [
                'feedbackMsg' => __('Sorry, it seems like this record has been removed.'),
            ]);
        } else {
            $this->state = [
                'overtimeId' => $this->request->overtime_id,
                'workToPerform' => $this->request->work_performed,
                'date' => Carbon::parse($this->request->date)->format('Y-m-d'),
                'startTime' => Carbon::parse($this->request->start_time)->format('H:i'),
                'endTime' => Carbon::parse($this->request->end_time)->format('H:i'),
            ];
            $this->hoursRequested = $this->request->getHoursRequested();
            
            $this->makeKeysReadable();

            if (Gate::allows('editOvertimeRequest', $this->request)) {
                $this->editMode = true;
            }

            $this->dispatch('openOvertimeRequestModal');
        }
    }

    private function getCutOffType()
    {
        $selectedDate = Carbon::parse($this->state['date']);
        return Payroll::getCutOffPeriodForDate($selectedDate);
    }

    private function makeKeysReadable()
    {
        $this->overtime = (object) [
            'filedAt'                   =>  $this->request->filed_at,
            'initiallyApprovedBy'       =>  $this->request?->processes?->first()?->initialApprover?->full_name,
            'initiallyApprovedAt'       =>  $this->request?->processes?->first()?->initial_approver_signed_at,
            'secondaryApprovedBy'       =>  $this->request?->processes?->first()?->secondaryApprover?->full_name,
            'secondaryApprovedAt'       =>  $this->request?->processes?->first()?->secondary_approver_signed_at,
            'deniedBy'                  =>  $this->request?->processes?->first()?->deniedBy?->full_name,
            'deniedAt'                  =>  $this->request?->processes?->first()?->denied_at,
            'feedback'                  =>  $this->request?->processes?->first()?->feedback,
        ];
    }

    #[Computed]
    private function employee()
    {
        return Auth::user()->account;
    }

    public function rules(): array
    {
        return [
            'state.workToPerform' => 'required|string|max:100',
            'state.date' => 'required|date|after_or_equal:today',
            'state.startTime' => 'required|date_format:H:i',
            'state.endTime' => 'required|date_format:H:i|after:state.startTime',
        ];
    }

    public function messages(): array
    {
        return [
            'state.workToPerform.required' => __('Please provide a description.'),
            'state.workToPerform.string' => __('Description must be a valid string.'),
            'state.workToPerform.max' => __('Description must not exceed 100 characters.'),
            'state.date.required' => __('Please provide a valid date.'),
            'state.date.date' => __('The date must be a valid format.'),
            'state.date.after_or_equal' => __('The date must not be in the past.'),
            'state.startTime.required' => __('Please provide a start time.'),
            'state.startTime.date_format' => __('The start time must be in the format HH:mm.'),
            'state.endTime.required' => __('Please provide an end time.'),
            'state.endTime.date_format' => __('The end time must be in the format HH:mm.'),
            'state.endTime.after' => __('The end time must be after the start time.'),
        ];
    }

    public function render()
    {
        return view('livewire.employee.overtimes.basic.edit-overtime-request');
    }
}

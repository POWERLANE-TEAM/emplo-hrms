<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Enums\Payroll;
use Livewire\Component;
use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Employee\Tables\Basic\RecentOvertimesTable;
use App\Livewire\Employee\Tables\Basic\ArchiveOvertimesTable;

class RequestOvertime extends Component
{
    public $state = [
        'overtimeId' => null,
        'workToPerform' => '',
        'date' => null,
        'startTime' => null,
        'endTime' => null,
    ];

    #[Locked]
    public $hoursRequested = 0;

    public function mount()
    {
        $this->state['date'] = now()->format('Y-m-d');
        $this->state['startTime'] = now()->format('H:i');
    }

    public function updated($prop)
    {
        if (
            in_array($prop, ['state.startTime', 'state.endTime']) &&
            isset($this->state['startTime'], $this->state['endTime'])
        ) {
            $start = Carbon::parse($this->state['startTime']);
            $end = Carbon::parse($this->state['endTime']);

            if ($end->lt($start)) {
                $this->addError('state.endTime', __('End time cannot be before the start date.'));
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
            $overtime = Overtime::create([
                'employee_id' => $this->employee->employee_id,
                'work_performed' => $this->state['workToPerform'],
                'date' => $this->state['date'],
                'start_time' => $this->state['startTime'],
                'end_time' => $this->state['endTime'],
                'cut_off' => $this->getCutOffType(),
            ]);
    
            $overtime->processes()->create([
                'processable_type' => Overtime::class,
                'processable_id' => $overtime->overtime_id,
            ]);
        });    

        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('changesSaved')->to(ArchiveOvertimesTable::class);
        $this->dispatch('changesSaved')->to(RecentOvertimesTable::class);
        $this->dispatch('changesSaved');
    }

    #[On('resetOvertimeRequestModal')]
    public function discard()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    private function getCutOffType()
    {
        $selectedDate = Carbon::parse($this->state['date']);
        return Payroll::getCutOffPeriodForDate($selectedDate);
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
        return view('livewire.employee.overtimes.basic.request-overtime');
    }
}

<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use App\Livewire\Employee\Tables\Basic\OvertimesTable;
use Livewire\Component;
use App\Models\Overtime;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestOvertime extends Component
{
    public $state = [
        'workToPerform' => '',
        'date' => null,
        'startTime' => null,
        'endTime' => null,
    ];

    public function rules(): array
    {
        return [
            'state.*' => 'required',
            // unique validation requirements for each state prop
        ];
    }

    public function messages(): array
    {
        return [
            'state.workToPerform' => __('Please provide a valid description.'),
            'state.date' => __('Please provide a valid date.'),
            'state.startTime' => __('Please provide a valid start time.'),
            'state.endTime' => __('Please provide a valid end time.')
        ];
    }

    public function save()
    {
        $this->authorize('submitOvertimeRequest', Auth::user());
        $this->authorize('submitOvertimeRequestToday', $this->employee);
        $this->authorize('submitNewOrAnotherOvertimeRequest', $this->employee);
        
        $this->validate();

        DB::transaction(function () {
            $overtime = Overtime::create([
                'employee_id' => Auth::id(),
                'work_performed' => $this->state['workToPerform'],
                'start_time' => $this->state['startTime'],
                'end_time' => $this->state['endTime'],
            ]);
    
            $overtime->processes()->create([
                'processable_type' => Overtime::class,
                'processable_id' => $overtime->overtime_id,
            ]);
        });

        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('overtime-request-created')->to(OvertimesTable::class);
        $this->dispatch('changes-saved');
    }

    #[Computed]
    private function employee()
    {
        return Auth::user()->account;
    }

    public function render()
    {
        return view('livewire.employee.overtimes.basic.request-overtime');
    }
}

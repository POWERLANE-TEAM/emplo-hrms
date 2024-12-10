<?php

namespace App\Livewire\Employee\Overtimes\Basic;

use Livewire\Component;
use App\Models\Overtime;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Employee\Tables\Basic\OvertimesTable;
use App\Livewire\Employee\Tables\Basic\RecentOvertimesTable;

class RequestOvertime extends Component
{
    public $state = [
        'workToPerform' => '',
        'date' => null,
        'startTime' => null,
        'endTime' => null,
    ];

    #[Locked]
    public $hoursRequested = 0;

    #[Locked]
    public $editMode = false;

    #[Locked]
    public $title = 'Request Overtime';

    #[Locked]
    public $buttonName = 'Submit Request';

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
                $this->resetErrorBag();
                $this->hoursRequested = $start->diffForHumans($end);
            }
        }
    }

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
        if ($this->editMode) {
            abort(403, 'Fuck you');
        }

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

        $this->dispatch('overtimeRequestCreated')->to(OvertimesTable::class);
        $this->dispatch('overtimeRequestCreated')->to(RecentOvertimesTable::class);
        $this->dispatch('changesSaved');
    }

    #[On('findModelId')]
    public function openEditMode(int $overtimeId)
    {
        $request = Overtime::find($overtimeId);

        if (! $request) {
            $this->dispatch('modelNotFound', [
                'feedbackMsg' => __('Sorry, it seems like this record has been removed.'),
            ]);
        } else {
            $this->state = [
                'workToPerform' => $request->work_performed,
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'startTime' => Carbon::parse($request->start_time)->format('H:i'),
                'endTime' => Carbon::parse($request->end_time)->format('H:i'),
            ];
            $this->editMode = true;
            $this->hoursRequested = $request->getHoursRequested();
            $this->buttonName = __('Save Changes');

            if (! Str::contains($this->title, '(Editing)')) {
                $this->title = Str::of($this->title)->append(' (Editing)');
            }

            $this->dispatch('openOvertimeRequestModal');
        }
    }

    #[On('abortAction')]
    public function discard()
    {
        $this->reset();
        $this->resetErrorBag();
        $this->dispatch('resetOvertimeRequestModal');
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

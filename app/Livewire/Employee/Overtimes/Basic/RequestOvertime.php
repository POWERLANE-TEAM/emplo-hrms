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
        'overtimeId' => null,
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
                $this->resetErrorBag('state.endTime');
                $this->hoursRequested = $start->diff($end)->format('%h hours and %i minutes');
            }
        }
    }

    public function save()
    {
        if ($this->editMode) {
            $overtime = Overtime::find($this->state['overtimeId']);

            if (! $overtime) {
                abort (403);
            }

            $this->authorize('updateOvertimeRequest', [$this->employee, $overtime]);

            $this->validate();

            DB::transaction(function () use ($overtime) {
                $overtime->update([
                    'employee_id' => Auth::id(),
                    'work_performed' => $this->state['workToPerform'],
                    'start_time' => $this->state['startTime'],
                    'end_time' => $this->state['endTime'],
                ]);
            });    
        } else {
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
        }

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
                'overtimeId' => $request->overtime_id,
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
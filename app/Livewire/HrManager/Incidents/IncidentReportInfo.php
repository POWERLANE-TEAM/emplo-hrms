<?php

namespace App\Livewire\HrManager\Incidents;

use App\Enums\FilePath;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Incident;
use App\Models\IssueType;
use App\Enums\IssueStatus;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Enums\IncidentPriorityLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncidentReportInfo extends Component
{
    use WithFileUploads;

    public Incident $incident;

    #[Locked]
    public $routePrefix;

    public $types = [];

    public $attachments = [];
    
    public $description;

    public $initiator;

    public $priority;

    public $status;

    public $resolutionDate;

    public $resolutionDetails;

    public function mount()
    {
        $this->types = $this->incident->types->map(function ($item) {
            return (string) $item->issue_type_id;
        });

        $this->dispatch('setInitialValues', $this->types);

        $this->attachments          = $this->incident->attachments;
        $this->description          = $this->incident->incident_description;
        $this->initiator            = $this->incident->initiator;
        $this->priority             = $this->incident->priority;
        $this->status               = $this->incident->status;
        $this->resolutionDate       = $this->incident?->resolution_date;
        $this->resolutionDetails    = $this->incident?->resolution;
    }

    public function save()
    {
        $this->authorize('createIncidentReport');

        $this->validate();

        DB::transaction(function () {
            $incident = $this->storeIncident();
            $incident->types()->attach($this->types);
            $this->storeAttachments($incident);
        });

        $this->reset();

        $this->dispatch('storedIncidentReport', [
            'type' => 'success',
            'message' => __("Incident report was successfully created.")
        ]);

        $this->dispatch('changes-saved');
    }

    public function storeIncident(): Incident
    {
        return Incident::create([
            'incident_description'  => $this->description,
            'resolution'            => $this->resolutionDetails,
            'status'                => $this->status,
            'priority'              => $this->priority,
            'initiator'             => $this->initiator,
            'reporter'              => Auth::user()->account->employee_id,
        ]);
    }

    public function storeAttachments(Incident $incident): void
    {
        Storage::disk('local')->makeDirectory(FilePath::INCIDENTS->value);

        $incidentAttachments = [];

        foreach ($this->attachments as $attachment) {

            $hashedVersion = $attachment->hashName();
            
            $attachment->storeAs(FilePath::INCIDENTS->value, $hashedVersion, 'local');

            array_push($incidentAttachments, [
                'attachment'        => $hashedVersion,
                'attachment_name'   => $attachment->getClientOriginalName(),
                'incident_id'       => $incident->incident_id,
            ]); 
        }

        DB::table('incident_attachments')->insert($incidentAttachments); 
    }

    public function removeAttachment(int $index)
    {
        $attachment = $this->attachments[$index]->getFilename();

        unset($this->attachments[$index]);

        Storage::disk('local')->delete("livewire-tmp/{$attachment}");
    }

    public function rules(): array
    {
        return [
            'types'             => 'required',
            'types.*'           => 'exists:issue_types,issue_type_id',
            'attachments'       => 'nullable|array|max:5',
            'attachments.*'     => 'file|max:51200',
            'description'       => 'required',
            'initiator'         => 'required',
            'priority'          => 'required',
            'status'            => 'required',
            'resolutionDate'    => 'nullable',
            'resolutionDetails' => 'nullable',
        ];
    }

    // public function messages()
    // {
    //     //
    // }

    #[Computed]
    public function incidentTypes()
    {
        return IssueType::all()
            ->pluck('issue_type_name', 'issue_type_id')
            ->toArray();
    }

    #[Computed]
    public function employees()
    {
        return Employee::all()
            ->mapWithKeys(function ($item) {
                return [$item->employee_id => $item->full_name];
            })
            ->toArray();
    }

    #[Computed]
    public function priorityLevels()
    {
        return IncidentPriorityLevel::options();
    }

    #[Computed]
    public function statuses()
    {
        return IssueStatus::options();
    }
    
    public function render()
    {
        return view('livewire.hr-manager.incidents.incident-report-info');
    }
}

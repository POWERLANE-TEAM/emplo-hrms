<?php

namespace App\Livewire\HrManager\Incidents;

use App\Enums\FilePath;
use App\Enums\IncidentPriorityLevel;
use App\Enums\IssueStatus;
use App\Models\Employee;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class IncidentReportInfo extends Component
{
    use WithFileUploads;

    public Incident $incident;

    #[Locked]
    public $routePrefix;

    public $attachments = [];

    public $description;

    public $initiator;

    public $priority;

    public $status;

    public $resolutionDate;

    public $resolutionDetails;

    public function mount()
    {
        $this->incident->loadMissing('types');

        $this->description = $this->incident->incident_description;
        $this->initiator = $this->incident->initiator;
        $this->priority = $this->incident->priority;
        $this->status = $this->incident->status;
        $this->resolutionDate = $this->incident?->resolution_date;
        $this->resolutionDetails = $this->incident?->resolution;
    }

    public function saveChanges()
    {
        $this->authorize('updateIncidentReport');

        // $this->validate();

        DB::transaction(function () {
            $incident = $this->updateIncident();
            $this->updateAttachments($incident);
        });

        $this->reset('attachments');

        $this->dispatch('updatedIncidentReport', [
            'type' => 'success',
            'message' => __('Incident report was successfully updated.'),
        ]);

        $this->dispatch('changes-saved');
    }

    public function updateIncident(): Incident
    {
        $this->incident->update([
            'incident_description' => $this->description,
            'resolution' => $this->resolutionDetails,
            'status' => $this->status,
            'priority' => $this->priority,
            'initiator' => $this->initiator,
        ]);

        return $this->incident->refresh();
    }

    private function updateAttachments(Incident $incident): void
    {
        if (count($this->attachments) === 0) {
            return;
        }

        Storage::disk('local')->makeDirectory(FilePath::INCIDENTS->value);

        $incidentAttachments = [];

        foreach ($this->attachments as $attachment) {

            $hashedVersion = $attachment->hashName();

            $attachment->storeAs(FilePath::INCIDENTS->value, $hashedVersion, 'local');

            array_push($incidentAttachments, [
                'attachment' => $hashedVersion,
                'attachment_name' => $attachment->getClientOriginalName(),
                'incident_id' => $incident->incident_id,
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
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:51200',
            'description' => 'required',
            'initiator' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'resolutionDate' => 'nullable',
            'resolutionDetails' => 'nullable',
        ];
    }

    public function messages()
    {
        //
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

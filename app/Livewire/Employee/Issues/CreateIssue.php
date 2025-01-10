<?php

namespace App\Livewire\Employee\Issues;

use App\Enums\FilePath;
use App\Models\Issue;
use Livewire\Component;
use App\Models\IssueType;
use App\Enums\IssueStatus;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Enums\IssueConfidentiality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateIssue extends Component
{
    use WithFileUploads;

    public $types = [];
    
    public $attachments = [];

    public $occuredAt;

    public $confidentiality;

    public $description;

    public $desiredResolution;

    public function mount()
    {
        unset($this->confidentialityPreferences);
    }

    public function save()
    {
        $this->authorize('submitIssueReport');

        $this->validate();

        DB::transaction(function () {
            $issue = $this->storeIssue();
            $issue->types()->attach($this->types);
            $this->storeAttachments($issue);
        });

        $this->reset();
        
        $this->dispatch('showSuccessToast', [
            'type' => 'success',
            'message' => __("Your report was successfully submitted.")
        ]);

        $this->dispatch('changes-saved');
    }

    private function storeIssue()
    {
        return Issue::create([
            'issue_reporter'        => Auth::user()->account->employee_id,
            'confidentiality'       => $this->confidentiality,
            'occured_at'            => $this->occuredAt,
            'issue_description'     => $this->description,
            'desired_resolution'    => $this->desiredResolution,
            'status'                => IssueStatus::OPEN,
            'status_marked_at'      => now(),
        ]);
    }

    private function storeAttachments(Issue $issue)
    {
        Storage::disk('local')->makeDirectory(FilePath::ISSUES->value);

        $issueAttachments = [];

        foreach ($this->attachments as $attachment) {

            $hashedVersion = sprintf('%s-%d', $attachment->hashName(), Auth::id());
            
            $attachment->storeAs(FilePath::ISSUES->value, $hashedVersion, 'local');

            array_push($issueAttachments, [
                'attachment'        => $hashedVersion,
                'attachment_name'   => $attachment->getClientOriginalName(),
                'issue_id'          => $issue->issue_id,
            ]); 
        }

        DB::table('issue_attachments')->insert($issueAttachments); 
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
            'occuredAt'         => 'nullable|date|before_or_equal:today',
            'confidentiality'   => 'required',
            'description'       => 'required|string',
            'attachments'       => 'nullable|array|max:5',
            'attachments.*'     => 'file|max:51200',
            'desiredResolution' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'         => __('Please select the type of issue'),
            'description.required'  => __('Please describe at least a summary or overview of this report.'),
        ];
    }

    #[Computed]
    public function issueTypes()
    {
        return IssueType::all()
            ->pluck('issue_type_name', 'issue_type_id')
            ->toArray();
    }

    #[Computed(persist: true)]
    public function confidentialityPreferences()
    {
        return IssueConfidentiality::options();
    }

    public function render()
    {
        return view('livewire.employee.issues.create-issue');
    }
}

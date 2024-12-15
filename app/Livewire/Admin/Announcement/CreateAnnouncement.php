<?php

namespace App\Livewire\Admin\Announcement;

use App\Enums\UserPermission;
use App\Models\Announcement;
use App\Models\JobFamily;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateAnnouncement extends Component
{
    #[Validate]
    public $state = [
        'title' => '',
        'visibleTo' => [],
        'description' => '',
    ];

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_ANNOUNCEMENT)) {
            $this->reset();

            abort(403);
        }
        $this->validate();

        DB::transaction(function () {
            $announcement = Announcement::create([
                'announcement_title' => $this->state['title'],
                'announcement_description' => $this->state['description'],
                'published_by' => Auth::user()->getAuthIdentifier(),
            ]);

            $announcement->offices()->attach($this->state['visibleTo']);
        });
        $this->reset();
        $this->resetErrorBag();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Announcement created successfully',
            'icon' => 'check-circle',
        ]);

        $this->dispatch('changes-saved');
    }

    public function rules()
    {
        return [
            'state.title' => 'required|string',
            'state.visibleTo' => 'required|min:1',
            'state.description' => 'nullable|string',
            'state.visibleTo.*' => 'integer|exists:job_families,job_family_id',
        ];
    }

    public function messages()
    {
        return [
            'state.title' => __('The announcement title is required.'),
            'state.visibleTo' => __('Please select the job family / offices that should see this announcement.'),
            'state.description' => __('Whatever'),
        ];
    }

    #[Computed]
    public function jobFamilies()
    {
        return JobFamily::all()->pluck('job_family_name', 'job_family_id')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.announcement.create-announcement');
    }
}

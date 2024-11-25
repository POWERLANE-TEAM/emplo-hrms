<?php

namespace App\Livewire\Guest;

use Livewire\Attributes\On;
use Livewire\Component;

class JobViewPane extends Component
{
    private $job_vacancy;

    public function mount(JobsListCard $jobsListCard)
    {
        $job = $jobsListCard->getJobVacancies()->first();
        $this->job_vacancy = [
            'jobDetail' => [
                'jobTitle' => [$job->jobTitle],
                'jobFamilies' => [$job->jobTitle->jobFamilies->first()],
                'specificAreas' => [$job->jobTitle->specificAreas->first()],
            ],
        ];
    }

    #[On('job-hiring-selected')]
    public function showJobVacancy($job_vacancy)
    {
        $this->job_vacancy = $job_vacancy;
    }

    #[On('job-searched')]
    public function resetView()
    {
        $this->job_vacancy = null;
    }

    public function placeholder()
    {
        return view('livewire.placeholder.job-view-pane');
    }

    public function render()
    {
        return view('livewire.guest.job-view-pane', ['job_vacancy' => $this->job_vacancy, 'lazy' => true]);
    }

    public function rendered()
    {
        $this->dispatch('guest-job-view-pane-rendered');
    }
}

<?php

namespace App\Livewire\Guest;

use App\Models\JobVacancy;
use Livewire\Attributes\On;
use Livewire\Component;

class JobViewPane extends Component
{
    private $job_vacancy;

    #[On('job-selected')]
    public function showJobVacancy($job_vacancy)
    {
        $this->placeholder();
        $this->job_vacancy = new JobVacancy($job_vacancy[0]);
        // dd($job_vacancy[0]);
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

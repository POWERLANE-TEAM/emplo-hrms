<?php

namespace App\Livewire\Guest;

use App\Models\JobVacancy;
use Livewire\Attributes\On;
use Livewire\Component;

class JobsListCard extends Component
{
    private $job_vacancies;

    private $isFiltered = false;

    #[On('job-searched')]
    public function updateOnSearch($search = null)
    {
        if (strlen(trim($search)) >= '1') {
            $result = JobVacancy::where('job_vacancy_id', 'ilike', '%' . $search . '%')
                ->orWhereHas('jobDetails.jobTitle', function ($query) use ($search) {
                    $query->where('job_title', 'ilike', '%' . $search . '%')
                        ->orWhere('job_desc', 'ilike', '%' . $search . '%');
                })
                ->get();
        } else {
            $result = JobVacancy::latest()->get();
        }
        $this->job_vacancies = $result;
        $this->isFiltered = true;
    }

    public function placeholder()
    {
        $this->job_vacancies = JobVacancy::with('jobDetails.jobTitle')->latest()->get();
        return view('livewire.placeholder.job-list-card', ['job_vacancies' => $this->job_vacancies]);
    }

    public function render()
    {

        if (! $this->isFiltered) {
            $this->job_vacancies = JobVacancy::with('jobDetails.jobTitle')->get();

            // dd($this->job_vacancies);
        }

        return view('livewire.guest.jobs-list-card', ['job_vacancies' => $this->job_vacancies]);
    }
}

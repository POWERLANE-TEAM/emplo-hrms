<?php

namespace App\Livewire\Guest;

use App\Models\JobVacancy;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class JobsListCard extends Component
{
    private $job_vacancies;

    private $isFiltered = false;

    private function getJobVacancies()
    {
        return JobVacancy::where('vacancy_count', '>', 1)
            ->whereDate('application_deadline_at', '>=', Carbon::today())
            ->with([
                'jobDetails.jobTitle',
                'jobDetails.specificArea',
                'jobDetails.jobFamily'
            ])
            ->latest()
            ->get();
    }

    #[On('job-searched')]
    public function updateOnSearch($search = null)
    {
        if (strlen(trim($search)) >= '1') {
            $result = JobVacancy::where('vacancy_count', '>', 1)
                ->whereDate('application_deadline_at', '>=', Carbon::today())
                ->where(function ($query) use ($search) {
                    $query->where('job_vacancy_id', 'ilike', '%' . $search . '%')
                        ->orWhereHas('jobDetails.jobTitle', function ($query) use ($search) {
                            $query->where('job_title', 'ilike', '%' . $search . '%')
                                ->orWhere('job_desc', 'ilike', '%' . $search . '%');
                        })
                        ->orWhereHas('jobDetails.specificArea', function ($query) use ($search) {
                            $query->where('area_name', 'ilike', '%' . $search . '%');
                        })
                        ->orWhereHas('jobDetails.jobFamily', function ($query) use ($search) {
                            $query->where('job_family_name', 'ilike', '%' . $search . '%');
                        });
                })
                ->with([
                    'jobDetails.jobTitle',
                    'jobDetails.specificArea',
                    'jobDetails.jobFamily'
                ])
                ->latest()
                ->get();
        } else {
            $result = $this->getJobVacancies();
        }
        $this->job_vacancies = $result;
        $this->isFiltered = true;
    }

    public function placeholder()
    {
        $this->job_vacancies = $this->getJobVacancies();

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

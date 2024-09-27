<?php

namespace App\Livewire\Guest;

use App\Models\JobVacancy;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class JobsListCard extends Component
{
    public $job_vacancies;

    private $isFiltered = false;

    private function getJobVacancies()
    {
        return JobVacancy::where('vacancy_count', '>', 1)
            ->where(function ($query) {
                $query->whereDate('application_deadline_at', '>=', Carbon::today())
                    ->orWhereNull('application_deadline_at');
            })
            ->with([
                'jobDetails.jobTitle',
                'jobDetails.specificArea',
                'jobDetails.jobFamily'
            ])
            ->latest()
            ->get();
    }

    protected function highlightSearchResults($row, $search)
    {
        if (isset($row->jobDetails->jobTitle->job_title)) {
            $row->jobDetails->jobTitle->job_title = $this->highlightSearchTerm($row->jobDetails->jobTitle->job_title, $search);
        }

        if (isset($row->jobDetails->jobTitle->job_desc)) {
            $row->jobDetails->jobTitle->job_desc = $this->highlightSearchTerm($row->jobDetails->jobTitle->job_desc, $search);
        }

        if (isset($row->jobDetails->specificArea->area_name)) {
            $row->jobDetails->specificArea->area_name = $this->highlightSearchTerm($row->jobDetails->specificArea->area_name, $search);
        }

        if (isset($row->jobDetails->jobFamily->job_family_name)) {
            $row->jobDetails->jobFamily->job_family_name = $this->highlightSearchTerm($row->jobDetails->jobFamily->job_family_name, $search);
        }

        return $row;
    }

    function highlightSearchTerm($text, $search)
    {
        $escapedKeyword = preg_quote($search, '/');
        return preg_replace_callback('/(' . $escapedKeyword . ')/i', function ($matches) {
            return "<mark>{$matches[1]}</mark>";
        }, $text);
    }

    protected function applyDeadlineFilter($query)
    {
        $query->whereDate('application_deadline_at', '>=', Carbon::today())
            ->orWhereNull('application_deadline_at');
    }

    protected function applySearchFilter($query, $search)
    {
        $query->where('job_vacancy_id', 'ilike', '%' . $search . '%')
            ->orWhereHas('jobDetails.jobTitle', fn($query) => $this->filterJobTitle($query, $search))
            ->orWhereHas('jobDetails.specificArea', fn($query) => $this->filterSpecificArea($query, $search))
            ->orWhereHas('jobDetails.jobFamily', fn($query) => $this->filterJobFamily($query, $search));
    }

    protected function filterJobTitle($query, $search)
    {
        $query->where('job_title', 'ilike', '%' . $search . '%')
            ->orWhere('job_desc', 'ilike', '%' . $search . '%');
    }

    protected function filterSpecificArea($query, $search)
    {
        $query->where('area_name', 'ilike', '%' . $search . '%');
    }

    protected function filterJobFamily($query, $search)
    {
        $query->where('job_family_name', 'ilike', '%' . $search . '%');
    }

    #[On('job-searched')]
    public function updateOnSearch($search = null)
    {
        if (strlen(trim($search)) >= '1') {
            $result = JobVacancy::where('vacancy_count', '>', 1)
                ->where(fn($query) => $this->applyDeadlineFilter($query))
                ->where(fn($query) => $this->applySearchFilter($query, $search))
                ->latest()
                ->get()
                ->map(fn($row) => $this->highlightSearchResults($row, $search));
        } else {
            $result = $this->getJobVacancies();
        }
        $this->job_vacancies = $result;
        $this->isFiltered = true;
    }

    public function placeholder()
    {
        // $this->job_vacancies = $this->getJobVacancies();

        return view('livewire.placeholder.job-list-card');
    }

    public function render()
    {

        if (! $this->isFiltered) {
            $this->job_vacancies = $this->getJobVacancies();

            // dd($this->job_vacancies);
        }

        foreach ($this->job_vacancies  as $jobVacancy) {
            $jobVacancy->jobDetails->jobTitle->makeHidden(['job_title_id']);
            $jobVacancy->jobDetails->specificArea->makeHidden(['area_id']);
            $jobVacancy->jobDetails->jobFamily->makeHidden(['job_family_id', 'office_head']);
        }


        return view('livewire.guest.jobs-list-card');
    }
}

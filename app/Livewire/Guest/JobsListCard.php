<?php

namespace App\Livewire\Guest;

use App\Models\JobDetail;
use App\Models\JobTitle;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class JobsListCard extends Component
{
    public $job_vacancies;

    private $is_filtered = false;

    private function baseJobVacancyQuery()
    {
        return JobVacancy::where('vacancy_count', '>', 1)
            ->where(function ($query) {
                $query->whereDate('application_deadline_at', '>=', Carbon::today())
                    ->orWhereNull('application_deadline_at');
            });
    }

    private function highlightText($text, $search)
    {
        return preg_replace_callback('/(' . preg_quote($search, '/') . ')/i', function ($matches) {
            return "<mark>{$matches[1]}</mark>";
        }, $text);
    }

    private function applySearchConditions($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->WhereHas('jobTitle', function ($query) use ($search) {
                $query->where('job_title', 'ilike', '%' . $search . '%')
                    ->orWhere('job_desc', 'ilike', '%' . $search . '%');
            })
                ->orWhereHas('jobTitle.specificAreas', function ($query) use ($search) {
                    $query->where('area_name', 'ilike', '%' . $search . '%');
                })
                ->orWhereHas('jobTitle.jobFamilies', function ($query) use ($search) {
                    $query->where('job_family_name', 'ilike', '%' . $search . '%');
                });
        });
    }

    private function highlightJobVacancies($job_vacancies, $search)
    {
        return $job_vacancies->map(function ($job_vacancy) use ($search) {

            if ($job_vacancy->jobTitle) {
                $job_vacancy->jobTitle->job_title = $this->highlightText($job_vacancy->jobTitle->job_title, $search);
                $job_vacancy->jobTitle->job_desc = $this->highlightText($job_vacancy->jobTitle->job_desc, $search);
            }

            if ($job_vacancy->jobTitle->specificAreas) {
                foreach ($job_vacancy->jobTitle->specificAreas as $specific_area) {
                    $specific_area->area_name = $this->highlightText($specific_area->area_name, $search);
                }
            }

            if ($job_vacancy->jobTitle->jobFamilies) {
                foreach ($job_vacancy->jobTitle->jobFamilies as $job_family) {
                    $job_family->job_family_name = $this->highlightText($job_family->job_family_name, $search);
                }
            }

            return $job_vacancy;
        });
    }

    private function getJobVacancies()
    {
        return $this->baseJobVacancyQuery()
            ->with(['jobDetails', 'jobTitle.jobFamilies', 'jobTitle.specificAreas'])
            ->latest()
            ->get();
    }

    #[On('job-searched')]
    public function updateOnSearch($search = null)
    {
        if (strlen(trim($search)) >= 1) {
            $query = $this->baseJobVacancyQuery()->with([
                'jobDetails',
                'jobTitle.specificAreas',
                'jobTitle.jobFamilies'
            ]);
            $this->applySearchConditions($query, $search);

            $result = $query->latest()->get();
            $result = $this->highlightJobVacancies($result, $search);
        } else {
            $result = $this->getJobVacancies();
        }

        $this->job_vacancies = $result;
        $this->is_filtered = true;
    }


    public function placeholder()
    {
        // $this->job_vacancies = $this->getJobVacancies();

        return view('livewire.placeholder.job-list-card');
    }

    public function render()
    {

        if (! $this->is_filtered) {
            $this->job_vacancies = $this->getJobVacancies();

            // dd($this->job_vacancies);
        }

        foreach ($this->job_vacancies as $job_vacancy) {
            $job_details = $job_vacancy->jobDetails;
            $job_title = $job_vacancy->jobTitle;

            if ($job_title) {
                $job_title->makeHidden(['job_title_id']);

                foreach ($job_title->specificAreas as $specific_area) {
                    $specific_area->makeHidden(['area_id']);
                }

                foreach ($job_title->jobFamilies as $job_family) {
                    $job_family->makeHidden(['job_family_id', 'office_head']);
                }
            }
        }


        return view('livewire.guest.jobs-list-card');
    }
}

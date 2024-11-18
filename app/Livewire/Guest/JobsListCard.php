<?php

namespace App\Livewire\Guest;

use App\Models\JobVacancy;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class JobsListCard extends Component
{
    public $job_vacancies;

    private $is_filtered = false;


    /**
     * Constructs the base query for fetching job vacancies.
     *
     * This method returns a query builder instance that filters job vacancies
     * based on the following criteria:
     * - The vacancy count must be greater than 1.
     * - The application deadline must be today or in the future, or it can be null.
     *
     * @return \Illuminate\Database\Eloquent\Builder The query builder instance for job vacancies.
     */
    private function baseJobVacancyQuery()
    {
        return JobVacancy::where('vacancy_count', '>', 1)
            ->where(function ($query) {
                $query->whereDate('application_deadline_at', '>=', Carbon::today())
                    ->orWhereNull('application_deadline_at');
            });
    }

    /**
     * Highlights the search term within the given text by wrapping it in <mark> tags.
     *
     * @param string $text The text to search within.
     * @param string $search The term to highlight in the text.
     *
     * @return string The text with the search term highlighted.
     */
    private function highlightText($text, $search)
    {
        return preg_replace_callback('/(' . preg_quote($search, '/') . ')/i', function ($matches) {
            return "<mark>{$matches[1]}</mark>";
        }, $text);
    }

    /**
     * Applies search conditions to the query based on the provided search term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $search The search term to apply to the query.
     * @return void
     */
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

    /**
     * Highlights the search term in the job vacancies' titles, descriptions, specific areas, and job families.
     *
     * @param \Illuminate\Support\Collection $job_vacancies The collection of job vacancies.
     * @param string $search The search term to highlight.
     * @return \Illuminate\Support\Collection The collection of job vacancies with highlighted search terms.
     */
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


    public function getJobVacancies()
    {
        return $this->baseJobVacancyQuery()
            ->with(['jobDetail', 'jobTitle.jobFamilies', 'jobTitle.specificAreas'])
            ->latest()
            ->get();
    }

    /**
     * Handles function delegation of searching job vacancies list based on the search term.
     *
     * This method listens for the 'job-searched' event and updates the job vacancies list
     * based on the provided search term. If the search term is null or empty, it resets the
     * filter flag.
     *
     * @param string|null $search The search term to filter job vacancies. If null or empty, retrieves all job vacancies.
     * @return void
     *
     *  Properties Modified:
     * @property bool $is_filtered A boolean flag indicating whether the job vacancies list is filtered based on the search term.
     */
    #[On('job-searched')]
    public function updateOnSearch(?String $search)
    {
        if (strlen(trim($search)) >= 1) {
            $query = $this->baseJobVacancyQuery()->with([
                'jobDetail',
                'jobTitle.specificAreas',
                'jobTitle.jobFamilies',
            ]);
            $this->applySearchConditions($query, $search);

            $result = $query->latest()->get();
            $result = $this->highlightJobVacancies($result, $search);
            $this->job_vacancies = $result;

            $this->is_filtered = true;
        } else {
            $this->is_filtered = false;
        }
    }

    /**
     * Generates a placeholder view for the job list card.
     * This function is used to display a skeleton layout while the actual content is still loading.
     *
     * @return \Illuminate\View\View The placeholder view for the job list card.
     */
    public function placeholder()
    {
        return view('livewire.placeholder.job-list-card');
    }

    public function render()
    {

        /**
         * Checks if the job vacancies list is filtered. If not, it retrieves all the job vacancies.
         */
        if (! $this->is_filtered) {
            $this->job_vacancies = $this->getJobVacancies();
        }

        /**
         * Iterates through each job vacancy and processes its job details and job title.
         *
         * This ensures that certain sensitive or unnecessary attributes are not exposed in the output.
         */
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

        $this->dispatch('job-vacancies-fetched', ['count' => $this->job_vacancies->count()]);
        return view('livewire.guest.jobs-list-card');
    }
}

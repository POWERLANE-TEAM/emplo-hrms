<?php

namespace App\Livewire\Employee\Tables\HRManager;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Implemented Methods:
 *
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 * @method  applyFullNameSearch(Builder $query, $searchTerm): Builder
 * @method  applyJobPositionSearch(Builder $query, $searchTerm): Builder
 * @method applyDateSearch(Builder $query, $searchTerm)
 */
class ApplicantsTable extends DataTableComponent
{
    protected $model = Application::class;

    /**
     * @var array contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function configure(): void
    {
        $routePrefix = auth()->user()->account_type;

        $this->setPrimaryKey('application_id')
            ->setTableRowUrl(function ($row) use ($routePrefix) {
                return route($routePrefix.'.application.show', $row);
            });

        $this->setEagerLoadAllRelationsEnabled();

        // $this->setSingleSortingDisabled();
        $this->setDefaultSort('applicants.created_at', 'desc');

        $this->setDefaultReorderSort('applicants.created_at', 'desc');

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTheadAttributes([
            'default' => true,
            'class' => '',
        ]);

        $this->setTbodyAttributes([
            'default' => true,
            'class' => '',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
        ]);

        $this->setTrimSearchStringEnabled();

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.table.filter.select-filter',
                [
                    'filter' => (function () {

                        // I need an early query to populate populate the query of options
                        // Although this is a duplicate the livewire table has not yet executed
                        // its builder method at this point of lifecycle
                        // I think this method is also called twice
                        $jobVacancies = JobVacancy::select('job_vacancy_id', 'job_detail_id')
                            ->with(['jobTitle' => function ($query) {
                                $query->select('job_titles.job_title_id', 'job_titles.job_title');
                            }])
                            ->withCount('applications')
                            ->get();

                        $withApplications = $jobVacancies->where('applications_count', '>', 0)
                            ->mapWithKeys(function ($vacancy) {
                                return [$vacancy->jobTitle->job_title_id => $vacancy->jobTitle->job_title];
                            })
                            ->toArray();

                        $this->customFilterOptions = [
                            '' => 'Select All Job Positions',
                        ] + $withApplications;

                        return SelectFilter::make('Position', 'jobPosition')
                            ->options($this->customFilterOptions)
                            ->filter(function (Builder $builder, string $value) {
                                if ($value !== '') {
                                    $this->dispatch('setFilter', 'jobPosition', $value);
                                }
                            })
                            ->setFirstOption('All Job Positions');
                    })(),
                ],
            ],
        ]);

        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-3']);

        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);

        $this->setPageName('job_application');
        $this->setPerPageAccepted([10, 25, 50, 100, -1]);

        $this->setThAttributes(function (Column $column) {

            return [
                'default' => true,
                'class' => 'text-center fw-normal',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'text-md-center',
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Full Name')
                ->label(fn ($row) => $row->applicant->fullName)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),

            Column::make('Job Position'/* 'vacancy.jobTitle.job_title' */)
                ->label(fn ($row) => $row->vacancy->jobTitle->job_title)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('job_title', $direction);
                })->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyJobPositionSearch($query, $searchTerm);
                }),

            Column::make('Date Applied')
                ->label(fn ($row) => $row->applicant->created_at->format('F j, Y') ?? 'An error occured.')
                ->setSortingPillDirections('Oldest first', 'Latest first')
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('applicants.created_at', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyDateSearch($query, $searchTerm);
                }),

            /**
             * |--------------------------------------------------------------------------
             * | Start of Additional Columns
             * |--------------------------------------------------------------------------
             * Description
             */
            Column::make('Department')
                ->label(fn ($row) => $row->vacancy->jobTitle->department->department_name)
                ->deselected(),

            Column::make('Job Area')
                ->label(fn ($row) => $row->vacancy->jobTitle->specificAreas->pluck('area_name')->join(', '))
                ->deselected(),

            Column::make('Job Level')
                ->label(fn ($row) => $row->vacancy->jobTitle->jobLevels->pluck('job_level_name')->join(', '))
                ->deselected(),
        ];
    }

    public function builder(): Builder
    {
        $query = Application::query()->with(['applicant', 'vacancy', 'vacancy.jobTitle.department', 'vacancy.jobTitle.specificAreas', 'vacancy.jobTitle.jobLevels'])

            // Without this I got SQLSTATE[42P01]: Undefined table: 7 ERROR: missing FROM-clause entry for table
            ->join('applicants', 'applications.applicant_id', '=', 'applicants.applicant_id')
            ->join('job_vacancies', 'applications.job_vacancy_id', '=', 'job_vacancies.job_vacancy_id')
            ->join('job_details', 'job_vacancies.job_detail_id', '=', 'job_details.job_detail_id')
            ->join('job_titles', 'job_details.job_title_id', '=', 'job_titles.job_title_id')
            ->join('departments', 'job_titles.department_id', '=', 'departments.department_id')
            ->join('specific_areas', 'job_details.area_id', '=', 'specific_areas.area_id')
            ->join('job_levels', 'job_details.job_level_id', '=', 'job_levels.job_level_id')
            ->where('hired_at', null);

        return $query;
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Date Applied From', 'application_from')
                ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
                ->config([
                    'min' => (function () {
                        $minDate = Applicant::has('application')->min('created_at');

                        return $minDate ? Carbon::parse($minDate)->format('Y-m-d') : now()->format('Y-m-d');
                    })(),
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                    'placeholder' => 'Enter Application Date',
                ])
                ->filter(function (Builder $builder, string $value) {
                    return $builder->whereDate('applicants.created_at', '>=', $value);
                }),

            SelectFilter::make('Job Position', 'jobPosition')
                ->options($this->customFilterOptions)
                ->filter(function (Builder $builder, string $value) {
                    return $query = $builder->where('job_titles.job_title_id', $value);
                })->hiddenFromMenus(),

        ];
    }

    /**
     * |--------------------------------------------------------------------------
     * | Column Searchable Queries
     * |--------------------------------------------------------------------------
     * Description
     */

    /**
     * Apply a search filter to the query based on the full name of the applicant.
     *
     * This method splits the search term into individual words and applies a case-insensitive
     * search on the first name, middle name, and last name of the applicant.
     *
     * @param  \Illuminate\Database\Query\Builder  $query  The query builder instance.
     * @param  string  $searchTerm  The search term to filter applicants by full name.
     * @return \Illuminate\Database\Query\Builder The modified query builder instance.
     */
    public function applyFullNameSearch(Builder $query, $searchTerm): Builder
    {
        $terms = explode(' ', $searchTerm);

        return $query->orWhereHas('applicant', function ($query) use ($terms) {
            foreach ($terms as $term) {
                $query->where(function ($query) use ($term) {
                    $query->where('first_name', 'ILIKE', "%{$term}%")
                        ->orWhere('middle_name', 'ILIKE', "%{$term}%")
                        ->orWhere('last_name', 'ILIKE', "%{$term}%");
                });
            }
        });
    }

    /**
     * Apply a case-insensitive search using the 'ILIKE' operator on the 'job_title' field in the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The query builder instance.
     * @param  string  $searchTerm  The term to search for in the job titles.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder instance with the search filter applied.
     */
    public function applyJobPositionSearch(Builder $query, $searchTerm): Builder
    {
        return $query->orWhereHas('vacancy.jobTitle', function ($query) use ($searchTerm) {
            $query->where('job_title', 'ILIKE', "%{$searchTerm}%");
        });
    }

    /**
     * Apply a date search filter to the query.
     *
     * This method normalizes the search term by removing spaces and then applies
     * a series of `orWhereRaw` conditions to the query to match the `created_at`
     * field of the `applicants` table against various date formats.
     *
     * Supported date formats:
     * - 'YYYY-MM-DD'
     * - 'MM/DD/YYYY'
     * - 'MM-DD-YYYY'
     * - 'Month DD, YYYY'
     * - 'Month YYYY'
     * - 'Month'
     * - 'DD-MM-YYYY'
     * - 'DD/MM/YYYY'
     *
     * @param  \Illuminate\Database\Query\Builder  $query  The query builder instance.
     * @param  string  $searchTerm  The search term to filter by.
     * @return \Illuminate\Database\Query\Builder The modified query builder instance.
     */
    public function applyDateSearch(Builder $query, $searchTerm)
    {
        $normalizedSearchTerm = str_replace(' ', '', $searchTerm);

        return $query->orWhereRaw("replace(to_char(applicants.created_at, 'YYYY-MM-DD'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'MM/DD/YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'MM-DD-YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'Month DD, YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'Month YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'Month'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'DD-MM-YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
            ->orWhereRaw("replace(to_char(applicants.created_at, 'DD/MM/YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"]);
    }
}

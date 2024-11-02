<?php

namespace App\Livewire\Employee\Tables\HRManager;

use App\Models\Applicant;
use App\Models\Application;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ApplicantsTable extends DataTableComponent
{
    protected $model = Application::class;

    protected $customFilterOptions;

    public function configure(): void
    {
        $this->setPrimaryKey('application_id');

        $this->setEagerLoadAllRelationsEnabled();

        $this->setSingleSortingDisabled();
        $this->setDefaultSort('applicants.created_at', 'desc');

        $this->setDefaultReorderSort('applicants.created_at', 'desc');

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1',
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
                'class' => 'border-1 rounded-2 outline ',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
        ]);

        $this->setTrimSearchStringEnabled();

        // $this->setReorderMethod('changeOrder');

        // $this->setHideReorderColumnUnlessReorderingEnabled();

        // $this->setReorderEnabled();

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

        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-md-3 my-md-2']);

        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);

        $this->setPageName('job_application');
        $this->setPerPageAccepted([10, 25, 50, 100, -1]);

        $this->setThAttributes(function (Column $column) {

            return [
                'default' => true,
                'class' => 'text-center',
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
            Column::make("Full Name")
                ->label(fn($row) => $row->applicant->getFullNameAttribute())
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),

            Column::make("Job Position", /* 'vacancy.jobTitle.job_title' */)
                ->label(fn($row) => $row->vacancy->jobTitle->job_title)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('job_title', $direction);
                })->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyJobPositionSearch($query, $searchTerm);
                }),

            Column::make("Date Applied",)
                ->label(fn($row) => $row->applicant->created_at->format('F j, Y') ?? 'An error occured.')
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

            Column::make("Department")
                ->label(fn($row) => $row->vacancy->jobTitle->department->department_name)
                ->deselected(),

            Column::make("Job Area")
                ->label(fn($row) => $row->vacancy->jobTitle->specificAreas->pluck('area_name')->join(', '))
                ->deselected(),

            Column::make("Job Level")
                ->label(fn($row) => $row->vacancy->jobTitle->jobLevels->pluck('job_level_name')->join(', '))
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
            ->where('hired_at', NULL);

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
                    return $query = $builder->where('job_titles.job_title_id',  $value);
                })->hiddenFromMenus(),

        ];
    }

    public function reorder(array $items): void
    {
        // $item[$this->getPrimaryKey()] ensures that the Primary Key is used to find the User
        // 'sort' is the name of your "sort" field in your database

        foreach ($items as $item) {
            Application::find($item[$this->getPrimaryKey()])->update(['sort' => (int)$item[$this->getDefaultReorderColumn()]]);
        }
    }

    public function actions(): array
    {
        return [
            Action::make('View Dashboard')
                ->setActionAttributes([
                    'class' => 'btn btn-primary',
                    'default-colors' => false,
                    'default-styling' => false
                ])->setRoute(route('employee.dashboard')),
        ];
    }

    /**
     * |--------------------------------------------------------------------------
     * | Column Searchable Queries
     * |--------------------------------------------------------------------------
     * Description
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

    public function applyJobPositionSearch(Builder $query, $searchTerm): Builder
    {
        return $query->orWhereHas('vacancy.jobTitle', function ($query) use ($searchTerm) {
            $query->where('job_title', 'ILIKE', "%{$searchTerm}%");
        });
    }

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


    /**
     * Called after configure is executed
     *
     *  See more hooks in https://rappasoft.com/docs/laravel-livewire-tables/v3/misc/lifecycle-hooks#content-configured
     *
     * @return void
     */
    public function configured()
    {
        // dd($this);
    }

    public function rowsRetrieved($rows)
    {

        // Tried to populate the the Application For Select Dropdown here but is late
        // The component is already proccesed or rendered
        // $withApplications = $rows->where('applications_count', '>', 0)
        //     ->mapWithKeys(function ($vacancy) {
        //         return [$vacancy->jobTitle->job_title_id => $vacancy->jobTitle->job_title];
        //     })
        //     ->toArray();

        // $this->customFilterOptions = $this->customFilterOptions + $withApplications;

        // $this->configurableAreas['toolbar-left-start'][1]['filter']->options = $this->customFilterOptions;
    }
}

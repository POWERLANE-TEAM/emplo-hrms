<?php

namespace App\Livewire\Employee\Tables\HRManager;

use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Http\Helpers\BeforeRoute;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Computed;
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
    use DefaultTableConfig;

    protected $model = Application::class;

    private $qualifiedState;

    public $status;

    /**
     * @var array contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function mount(string $applicationStatus)
    {

        $user = auth()->user();

        // dd($this->getEnumValues($this->qualifiedState));
        if ($applicationStatus == 'qualified') {
            $this->status = $this->getEnumValues(ApplicationStatus::qualifiedState());

            if (!$user->hasPermissionTo(UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS)) {
                abort(403);
            }
        } else {
            $statusValue = ApplicationStatus::fromNameSubstring($applicationStatus);

            if ($statusValue == ApplicationStatus::PENDING->value && !$user->hasPermissionTo(UserPermission::VIEW_ALL_PENDING_APPLICATIONS)) {
                abort(403);
            } else if ($statusValue == ApplicationStatus::PRE_EMPLOYED->value && !$user->hasPermissionTo(UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS)) {
                abort(403);
            }

            $this->status = $statusValue !== null ? [$statusValue] : [];
        }
    }

    public function boot(): void
    {
        $this->setTimezone();
        // dump(session()->all());
    }


    private function getEnumValues($values)
    {
        return array_map(fn($item) => $item->value, $values);
    }

    #[Computed]
    private function getJobVacancies(): Builder
    {
        return JobVacancy::select('job_vacancy_id', 'job_title_id')
            ->with(['jobTitle' => function ($query) {
                $query->select('job_titles.job_title_id', 'job_titles.job_title');
            }])
        ;
    }

    private function getJobVacanciesWithApplications()
    {
        return $this->getJobVacancies()
            ->withCount('applications')
            ->get();
    }

    private function getJobVacanciesWithApplicationsArray()
    {
        return $this->getJobVacanciesWithApplications()
            ->where('applications_count', '>', 0)
            ->mapWithKeys(function ($vacancy) {
                return [$vacancy->jobTitle->job_title_id => $vacancy->jobTitle->job_title];
            })
            ->toArray();
    }

    public function configure(): void
    {
        $routePrefix = auth()->user()->account_type;
        $this->setPrimaryKey('application_id')
            ->setTableRowUrl(function ($row) use ($routePrefix) {
                // dd(BeforeRoute::checkRoutePermission(route($routePrefix . '.application.show', $row)));
                // // if (!BeforeRoute::checkRoutePermission($routePrefix . '.application.show')) {
                // //     $currentRoute = Route::currentRouteName();
                // //     // dd($currentRoute);
                // //     return route($currentRoute);
                // // }

                return route($routePrefix . '.application.show', $row);
            });

        $this->configuringStandardTableMethods();

        // To avoid the switching applicant status column bug
        $this->setRememberColumnSelectionDisabled();

        // $this->setSingleSortingDisabled();
        $this->setDefaultSort('applicants.created_at', 'desc');

        $this->setDefaultReorderSort('applicants.created_at', 'desc');


        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.table.filter.select-filter',
                [
                    'filter' => (function () {

                        // I need an early query to populate populate the query of options
                        // Although this is a duplicate the livewire table has not yet executed
                        // its builder method at this point of lifecycle
                        // I think this method is also called twice
                        $withApplications = $this->getJobVacanciesWithApplicationsArray();

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

                    'label' => 'Applicants for:',
                ],
            ],
        ]);

        $this->setPageName('job_application');
    }

    public function columns(): array
    {
        return [
            Column::make('Full Name')
                ->label(function ($row) {
                    return $row->applicant->full_name;
                })
                ->sortable(function ($query, $direction) {
                    $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);

                    // $query->with(['applicant' => function ($query) use ($direction) {
                    //     $query->orderBy('last_name', $direction)
                    //         ->orderBy('first_name', $direction)
                    //         ->orderBy('middle_name', $direction);
                    // }]);

                    return $query;
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),

            Column::make('Job Position'/* 'vacancy.jobTitle.job_title' */)
                ->label(fn($row) => $row->vacancy->jobTitle->job_title)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('job_title', $direction);
                })->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyJobPositionSearch($query, $searchTerm);
                }),

            Column::make('Examination')
                ->label(function ($row) {
                    // $exam = ApplicationExam::where('application_id', $row->application_id)->first();

                    $startTime = $row->start_time;
                    return $startTime ? Carbon::parse($startTime)->setTimezone($this->timezone)->format('m/d/y - h:i A') : 'No Exam Scheduled';
                })
                ->selectedIf(fn() => !empty(array_intersect($this->status, $this->getEnumValues(ApplicationStatus::qualifiedState())))),

            Column::make('Interview')
                ->label(function ($row) {

                    $startTime = $row->init_interview_at;
                    return $startTime ? Carbon::parse($startTime)->setTimezone($this->timezone)->format('m/d/y - h:i A') : 'No Interview Scheduled';
                })
                ->selectedIf(fn() => !empty(array_intersect($this->status, $this->getEnumValues(ApplicationStatus::qualifiedState())))),

            Column::make('Final Interview')
                ->label(function ($row) {

                    $startTime = $row->final_interview_at;
                    return $startTime ? Carbon::parse($startTime)->setTimezone($this->timezone)->format('m/d/y - h:i A') : 'No Interview Scheduled';
                })
                ->deselected(),

            Column::make('Date Applied')
                ->label(fn($row) => $row->applicant->created_at->format('F j, Y') ?? 'An error occured.')
                ->setSortingPillDirections('Oldest first', 'Latest first')
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('applicants.created_at', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyDateSearch($query, $searchTerm);
                })
                ->deselectedIf(fn() => !empty(array_filter($this->status, fn($status) => $status > 1))),

            /**
             * |--------------------------------------------------------------------------
             * | Start of Additional Columns
             * |--------------------------------------------------------------------------
             * Description
             */
            Column::make('Department')
                ->label(fn($row) => $row->vacancy->jobTitle->department->department_name)
                ->deselected(),

            // // I dunno how to know which area applicant has applied for
            // Column::make('Job Area')
            //     ->label(fn ($row) => $row->vacancy->jobTitle->specificArea->area_name)
            //     ->deselected(),

            Column::make('Job Level')
                ->label(fn($row) => $row->vacancy->jobTitle->jobLevel->job_level_name)
                ->deselected(),
        ];
    }

    public function builder(): Builder
    {
        $query = Application::query()/* ->with(['applicant', 'vacancy', 'vacancy.jobTitle.department',  'vacancy.jobTitle.jobLevel']) */

            // Without this I got SQLSTATE[42P01]: Undefined table: 7 ERROR: missing FROM-clause entry for table
            // I now know that I need to join the table to be able for the sort acually work
            // using with() alone does not do any sorting
            ->join('applicants', 'applications.applicant_id', '=', 'applicants.applicant_id')
            ->join('job_vacancies', 'applications.job_vacancy_id', '=', 'job_vacancies.job_vacancy_id')
            ->join('job_titles', 'job_vacancies.job_title_id', '=', 'job_titles.job_title_id')
            ->where('hired_at', null)
            ->whereIn('application_status_id', $this->status)
            ->select('*', 'applications.*');

        if (!empty(array_intersect($this->status, $this->getEnumValues(ApplicationStatus::qualifiedState()))) || in_array(ApplicationStatus::PRE_EMPLOYED->value, $this->status)) {
            $query->leftJoin('application_exams', 'applications.application_id', '=', 'application_exams.application_id')
                ->leftJoin('initial_interviews', 'applications.application_id', '=', 'initial_interviews.application_id')
                ->leftJoin('final_interviews', 'applications.application_id', '=', 'final_interviews.application_id')
                ->whereNotNull('application_exams.application_id');
        }

        if (in_array(ApplicationStatus::PRE_EMPLOYED->value, $this->status)) {
            $query->where('final_interviews.is_final_interview_passed', true)
                ->where('final_interviews.is_job_offer_accepted', true);
        }

        // dump($query->toSql(), $query->getBindings());

        // $results = $query->limit(5)->get();
        // dd($results);

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
}

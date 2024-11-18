<?php

namespace App\Livewire\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\JobTitle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Implemented Methods:
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class EmployeesTable extends DataTableComponent
{
    protected $model = Employee::class;

    /**
     * @var array $customFilterOptions contains the dropdown values and keys.
     */
    protected $departments;

    protected $jobTitles;

    protected $employmentStatuses;

    private $oldestDate;

    public function configure(): void
    {
        $this->setPrimaryKey('application_id');

        $this->setEagerLoadAllRelationsEnabled();

        // $this->setDefaultSort('applications.hired_at', 'desc');

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
        ]);

        $this->setTrimSearchStringEnabled();

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag(['class' => 'fs-4 fw-bold']),
                    // 'containerAttributes' => new ComponentAttributeBag(['class' => '']),
                    'heading' => 'List of Employees',
                ],
            ],
        ]);

        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-3']);

        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);

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

        $this->jobTitles = JobTitle::select('job_title_id', 'job_title')->orderBy('job_title', 'ASC')->get()
            ->mapWithKeys(function ($jobTitle) {
                return [$jobTitle->job_title_id => $jobTitle->job_title];
            })
            ->prepend('Select All Job Positions', '')
            ->toArray();

        $this->employmentStatuses = EmploymentStatus::select('emp_status_id', 'emp_status_name')->orderBy('emp_status_name', 'ASC')->get()
            ->mapWithKeys(function ($employmentStatus) {
                return [$employmentStatus->emp_status_id => $employmentStatus->emp_status_name];
            })
            ->prepend('Select All Employee Status', '')
            ->toArray();

        $this->oldestDate = Employee::has('application')->join('applications', 'employees.application_id', '=', 'applications.application_id')->min('applications.hired_at');
    }

    public function columns(): array
    {
        return [
            Column::make("Full Name")
                ->label(fn($row) => $row->fullname)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction)
                    ;
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),
            Column::make("Job Title")
                ->label(fn($row) => $row->jobTitle->job_title)
                ->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyJobPositionSearch($query, $searchTerm);
                }),
            Column::make("Department")
                ->label(fn($row) => $row->jobTitle->department->department_name),

            Column::make("Employment")
                ->label(fn($row) => $row->employmentStatus->emp_status_name),

            /**
             * |--------------------------------------------------------------------------
             * | Start of Additional Columns
             * |--------------------------------------------------------------------------
             * Description
             */

            Column::make("Shift")
                ->label(fn($row) => $row->shift->shift_name)
                ->deselected(),

            Column::make("Hired Date")
                ->label(fn($row) => Carbon::parse($row->application->hired_at)->format('F j, Y') ?? 'No recorded.')
                /*                 ->setSortingPillDirections('Oldest first', 'Latest first')
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('applications.hired_at', $direction);
                }) */
                ->deselected(),


        ];
    }

    public function builder(): Builder
    {
        $query = Employee::query()->with(['employmentStatus', 'jobTitle.department', 'jobTitle.specificAreas', 'jobTitle.jobLevels', 'application', 'shift'])

            // Without this I got SQLSTATE[42P01]: Undefined table: 7 ERROR: missing FROM-clause entry for table
            ->join('employment_statuses', 'employees.emp_status_id', '=', 'employment_statuses.emp_status_id')
            ->join('job_details', 'employees.job_detail_id', '=', 'job_details.job_detail_id')
            ->join('job_titles', 'job_details.job_title_id', '=', 'job_titles.job_title_id')
            ->join('departments', 'job_titles.department_id', '=', 'departments.department_id')
            ->join('specific_areas', 'job_details.area_id', '=', 'specific_areas.area_id')
            ->join('job_levels', 'job_details.job_level_id', '=', 'job_levels.job_level_id')
            ->join('applications', 'employees.application_id', '=', 'applications.application_id')
            ->join('shifts', 'employees.shift_id', '=', 'shifts.shift_id');

        // Maybe add specific area restriction based on permission?

        $areaId = auth()->user()->account->jobTitle->specificAreas->first()->area_id;

        if ($areaId != 2) {
            $query->where('specific_areas.area_id', $areaId);
        }

        return $query;
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Hire Date', 'hired-date')
                ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
                ->config([
                    'min' => (function () {
                        return $this->oldestDate ? Carbon::parse($this->oldestDate)->format('Y-m-d') : now()->format('Y-m-d');
                    })(),
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                    'placeholder' => 'Enter Date',
                ])
                ->filter(function (Builder $builder, string $value) {
                    return;
                }),



            SelectFilter::make('Job Title', 'job-title')
                ->options($this->jobTitles)
                ->filter(function (Builder $builder, string $value) {
                    return $query = $builder->where('job_titles.job_title_id',  $value);
                }),

            SelectFilter::make('Employment Status', 'emp-status')
                ->options($this->employmentStatuses)
                ->filter(function (Builder $builder, string $value) {
                    return $query = $builder->where('employment_statuses.emp_status_id',  $value);
                }),


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

        foreach ($terms as $term) {
            $query->orWhere(function ($query) use ($term) {
                $query->where('first_name', 'ILIKE', "%{$term}%")
                    ->orWhere('middle_name', 'ILIKE', "%{$term}%")
                    ->orWhere('last_name', 'ILIKE', "%{$term}%");
            });
        }

        return $query;
    }

    /**
     * Apply a case-insensitive search using the 'ILIKE' operator on the 'job_title' field in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $searchTerm The term to search for in the job titles.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder instance with the search filter applied.
     */
    public function applyJobPositionSearch(Builder $query, $searchTerm): Builder
    {
        return $query->orWhereHas('jobTitle', function ($query) use ($searchTerm) {
            $query->where('job_title', 'ILIKE', "%{$searchTerm}%");
        });
    }

    /**
     * Apply a date search filter to the query.
     *
     * This method normalizes the search term by removing spaces and then applies
     * a series of `orWhereRaw` conditions to the query to match the `created_at`
     * field of the table against various date formats.
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
     * @param \Illuminate\Database\Query\Builder $query The query builder instance.
     * @param string $searchTerm The search term to filter by.
     * @return \Illuminate\Database\Query\Builder The modified query builder instance.
     */
    // public function applyDateSearch(Builder $query, $searchTerm)
    // {
    //     $normalizedSearchTerm = str_replace(' ', '', $searchTerm);
    //     return $query->orWhereRaw("replace(to_char(created_at, 'YYYY-MM-DD'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'MM/DD/YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'MM-DD-YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'Month DD, YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'Month YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'Month'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'DD-MM-YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'DD/MM/YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"]);
    // }
}

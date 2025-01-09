<?php

namespace App\Livewire\Tables;

use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\JobTitle;
use Carbon\Carbon;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
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
 */
class EmployeesTable extends DataTableComponent
{
    use DefaultTableConfig;

    protected $model = Employee::class;

    /**
     * @var array contains the dropdown values and keys.
     */
    protected $departments;

    protected $jobTitles;

    protected $employmentStatuses;

    private $oldestDate;

    public function configure(): void
    {
        $routePrefix = auth()->user()->account_type;

        $this->setPrimaryKey('application_id')
            ->setTableRowUrl(function ($row) use ($routePrefix) {

                return route($routePrefix . '.employees.masterlist.information', ['employee' => $row->employee_id]) . '/#information';
            })
            ->setTableRowUrlTarget(fn() => '__blank');


        $this->configuringStandardTableMethods();

        // $this->setDefaultSort('applications.hired_at', 'desc');


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

        $this->oldestDate = Employee::whereHas('application')->with('application')->get()->min(function ($employee) {
            return $employee->application->hired_at;
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Full Name')
                ->label(fn($row) => $row->fullname)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    // $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),
            Column::make('Job Title')
                ->label(fn($row) => $row->jobTitle->job_title)
                ->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyJobPositionSearch($query, $searchTerm);
                }),
            Column::make('Department')
                ->label(fn($row) => $row->jobTitle->department->department_name),

            Column::make('Employment')
                ->label(fn($row) => $row->status->emp_status_name),

            /**
             * |--------------------------------------------------------------------------
             * | Start of Additional Columns
             * |--------------------------------------------------------------------------
             * Description
             */
            Column::make('Shift')
                ->label(fn($row) => $row->shift->shift_name)
                ->deselected(),

            Column::make('Hired Date')
                ->label(fn($row) => $row->application ? Carbon::parse($row->application->hired_at)->format('F j, Y') : 'No recorded.')
                ->setSortingPillDirections('Oldest first', 'Latest first')
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('applications.hired_at', $direction);
                })
                ->deselected(),

        ];
    }

    public function builder(): Builder
    {
        $query = Employee::query()->with(['status', 'jobTitle.department', 'specificArea', 'jobTitle.jobLevel', 'application', 'shift'])

            // Without this I got SQLSTATE[42P01]: Undefined table: 7 ERROR: missing FROM-clause entry for table
            // some joins are not currently in use but may be needed in sorting and filtering.
            ->leftJoin('employee_job_details', 'employees.employee_id', '=', 'employee_job_details.employee_id')
            ->leftJoin('employment_statuses', 'employee_job_details.emp_status_id', '=', 'employment_statuses.emp_status_id')
            ->leftJoin('job_titles', 'employee_job_details.job_title_id', '=', 'job_titles.job_title_id')
            ->join('departments', 'job_titles.department_id', '=', 'departments.department_id')
            ->leftJoin('specific_areas', 'employee_job_details.area_id', '=', 'specific_areas.area_id')
            ->join('job_levels', 'job_titles.job_level_id', '=', 'job_levels.job_level_id')
            ->leftJoin('applications', 'employee_job_details.application_id', '=', 'applications.application_id')
            ->leftJoin('shifts', 'employee_job_details.shift_id', '=', 'shifts.shift_id');

        // $this->limitSpecificArea($query);

        return $query;
    }

    public function filters(): array
    {
        return [
            // DateFilter::make('Hire Date', 'hired-date')
            //     ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
            //     ->config([
            //         'min' => (function () {
            //             return $this->oldestDate ? Carbon::parse($this->oldestDate)->format('Y-m-d') : now()->format('Y-m-d');
            //         })(),
            //         'max' => now()->format('Y-m-d'),
            //         'pillFormat' => 'd M Y',
            //         'placeholder' => 'Enter Date',
            //     ])
            //     ->filter(function (Builder $builder, string $value) {}),

            SelectFilter::make('Job Title', 'job-title')
                ->options($this->jobTitles)
                ->filter(function (Builder $builder, string $value) {
                    return $query = $builder->where('job_titles.job_title_id', $value);
                }),

            SelectFilter::make('Employment Status', 'emp-status')
                ->options($this->employmentStatuses)
                ->filter(function (Builder $builder, string $value) {
                    return $query = $builder->where('employment_statuses.emp_status_id', $value);
                }),

        ];
    }

    /**
     * |--------------------------------------------------------------------------
     * | Column Searchable Queries
     * |--------------------------------------------------------------------------
     * Description
     */


    /**
     * Apply a case-insensitive search using the 'ILIKE' operator on the 'job_title' field in the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The query builder instance.
     * @param  string  $searchTerm  The term to search for in the job titles.
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
     * @param  \Illuminate\Database\Query\Builder  $query  The query builder instance.
     * @param  string  $searchTerm  The search term to filter by.
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

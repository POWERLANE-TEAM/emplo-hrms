<?php

namespace App\Livewire\Tables;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\JobTitle;
use Illuminate\Support\Str;
use App\Models\EmploymentStatus;
use App\Enums\EmploymentStatus as Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
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

                return route($routePrefix . '.employees.information', ['employee' => $row->employee_id]) . '/#information';
            })
            ->setTableRowUrlTarget(fn() => '__blank');

        $this->configuringStandardTableMethods();

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

        $this->jobTitles = JobTitle::select('job_title_id', 'job_title')
            ->orderBy('job_title', 'ASC')
            ->get()
            ->mapWithKeys(function ($jobTitle) {
                return [$jobTitle->job_title_id => $jobTitle->job_title];
            })
            ->prepend('Select All Job Positions')
            ->toArray();

        $this->employmentStatuses = EmploymentStatus::select('emp_status_id', 'emp_status_name')
            ->orderBy('emp_status_name', 'ASC')
            ->get()
            ->filter(fn ($item) => in_array($item->emp_status_name, [
                Status::PROBATIONARY->label(),
                Status::REGULAR->label(),
            ]))
            ->mapWithKeys(function ($employmentStatus) {
                return [$employmentStatus->emp_status_id => $employmentStatus->emp_status_name];
            })
            ->prepend('Select All Employee Status')
            ->toArray();

        $this->oldestDate = Employee::whereHas('application')->with('application')->get()->min(function ($employee) {
            return $employee->application->hired_at;
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => $columnIndex === 0 ? 'text-md-start' : 'text-md-center',
            ];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Full Name')
                ->label(function ($row) {
                    $name = Str::headline($row->full_name);
                    $photo = $row->account->photo;
                    $id = $row->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name' ,$direction))
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereLike('first_name', "%{$searchTerm}%")
                        ->orWhereLike('middle_name', "%{$searchTerm}%")
                        ->orWhereLike('last_name', "%{$searchTerm}%")
                        ->orWhereHas('account', fn ($query) => $query->orWhereLike('email', "%{$searchTerm}%"));
                })
                ->excludeFromColumnSelect(),

            Column::make('Job Title')
                ->label(fn($row) => $row->jobTitle->job_title)
                ->searchable(function (Builder $query, $searchTerm) {
                    return $this->applyJobPositionSearch($query, $searchTerm);
                }),

            Column::make('Job Family')
                ->label(fn ($row) => $row->jobTitle->jobFamily->job_family_name)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('job_family_name' ,$direction))
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('job_family_name', "%{$searchTerm}%")),

            Column::make('Department')
                ->label(fn($row) => $row->jobTitle->department->department_name),

            Column::make('Status')
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
                ->label(fn($row) => $row->application ? Carbon::parse($row->application->hired_at)->format('F j, Y') : 'No record found.')
                ->setSortingPillDirections('Oldest first', 'Latest first')
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('applications.hired_at', $direction);
                })
                ->deselected(),
        ];
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->with([
                'account',
                'status',
                'jobTitle' => [
                    'department',
                    'jobLevel',
                    'jobFamily',
                ],
                'specificArea',
                'application',
                'shift'
            ])
                ->whereHas('status', function ($query) {
                    $query->whereIn('emp_status_name', [
                        Status::PROBATIONARY->label(),
                        Status::REGULAR->label(),
                    ]);
                });
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

            SelectFilter::make('Job Title')
                ->options($this->jobTitles)
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereHas('jobDetail.jobTitle', function ($subQuery) use ($value) {
                        $subQuery->where('job_title_id', $value);
                    });
                }),

            SelectFilter::make('Employment Status')
                ->options($this->employmentStatuses)
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereHas('jobDetail.status', function ($subQuery) use ($value) {
                        $subQuery->where('emp_status_id', $value);
                    });
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

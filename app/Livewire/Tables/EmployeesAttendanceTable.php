<?php

namespace App\Livewire\Tables;

use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\JobTitle;
use Carbon\Carbon;
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
class EmployeesAttendanceTable extends DataTableComponent
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

    public $attendanceDate;

    public function configure(): void
    {
        $this->setPrimaryKey('application_id');

        $this->configuringStandardTableMethods();

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {

            if (in_array($columnIndex, [3, 4])) {
                // Arrival and Status column
                return [
                    'class' => 'text-md-center text-capitalize',
                ];
            }

            return [
                'class' => 'text-md-center',
            ];
        });

        $this->attendanceDate = Carbon::now()->format('Y-m-d');

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag(['class' => 'fs-4 fw-bold']),
                    'heading' => Carbon::parse($this->attendanceDate)->format('F j, Y'),
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

        $this->oldestDate = Employee::has('attendances')->join('attendances', 'employees.employee_id', '=', 'attendances.employee_id')->min('attendances.time_in');
    }

    public function columns(): array
    {
        return [
            Column::make('Full Name')
                ->label(fn ($row) => $row->fullname)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),

            Column::make('Time In')
                ->label(fn ($row) => optional($row->attendances->first())->time_in ? Carbon::parse($row->attendances->first()->time_in)->format('h:i A') : '-'),

            Column::make('Time Out')
                ->label(fn ($row) => optional($row->attendances->first())->time_out ? Carbon::parse($row->attendances->first()->time_out)->format('h:i A') : '-'),

            Column::make('Arrival')
                ->label(function ($row) {
                    $attendance = optional($row->attendances->first())->time_in;
                    $shiftStartTime = Carbon::parse($row->shift->start_time);
                    $now = Carbon::now();

                    if (is_null($attendance)) {
                        return $now->lessThan($shiftStartTime) ? 'arriving' : 'running late';
                    } else {
                        $attendanceTime = Carbon::parse($attendance);

                        return $attendanceTime->lessThan($shiftStartTime) ? 'on time' : 'late';
                    }
                }),

            Column::make('Status')
                ->label(function ($row) {
                    $attendance = optional($row->attendances->first());
                    $timeIn = $attendance->time_in;
                    $timeOut = $attendance->time_out;
                    $shiftEndTime = Carbon::parse($row->shift->end_time);
                    $now = Carbon::now();

                    // Temporary
                    if (is_null($timeOut)) {
                        if ($now->lessThan($shiftEndTime)) {
                            return 'on duty';
                        } elseif ($now->diffInMinutes($shiftEndTime) <= 30) {
                            return 'duty ended';
                        } else {
                            return 'overtime';
                        }
                    } else {
                        return 'clock out';
                    }
                }),

            /**
             * |--------------------------------------------------------------------------
             * | Start of Additional Columns
             * |--------------------------------------------------------------------------
             * Description
             */
            Column::make('Shift')
                ->label(fn ($row) => $row->shift->shift_name)
                ->deselected(),

            Column::make('Job Title')
                ->label(fn ($row) => $row->jobTitle->job_title)
                ->deselected(),

            Column::make('Department')
                ->label(fn ($row) => $row->jobTitle->department->department_name)
                ->deselected(),

            Column::make('Employment')
                ->label(fn ($row) => $row->employmentStatus->emp_status_name)
                ->deselected(),

            Column::make('Hired Date')
                ->label(fn ($row) => Carbon::parse($row->application->hired_at)->format('F j, Y') ?? 'No recorded.')
                ->deselected(),

        ];
    }

    public function builder(): Builder
    {
        $query = Employee::query()->with(['attendances', 'employmentStatus', 'jobTitle.department', 'jobTitle.specificAreas', 'jobTitle.jobLevels', 'application', 'shift'])

            // Without this I got SQLSTATE[42P01]: Undefined table: 7 ERROR: missing FROM-clause entry for table
            ->join('attendances', 'employees.employee_id', '=', 'attendances.employee_id')
            ->join('employment_statuses', 'employees.emp_status_id', '=', 'employment_statuses.emp_status_id')
            ->join('job_details', 'employees.job_detail_id', '=', 'job_details.job_detail_id')
            ->join('job_titles', 'job_details.job_title_id', '=', 'job_titles.job_title_id')
            ->join('departments', 'job_titles.department_id', '=', 'departments.department_id')
            ->join('specific_areas', 'job_details.area_id', '=', 'specific_areas.area_id')
            ->join('applications', 'employees.application_id', '=', 'applications.application_id')
            ->join('shifts', 'employees.shift_id', '=', 'shifts.shift_id')

            // prevent duplicate rows (workaround)
            ->distinct('employees.employee_id');

        // Maybe add specific area restriction based on permission?

        $areaId = auth()->user()->account->jobTitle->specificAreas->first()->area_id;

        if ($areaId != 2) {
            $query->where('specific_areas.area_id', $areaId);
        }

        if (! $this->getAppliedFilterWithValue('attendance-date')) {
            $query->with(['attendances' => function ($query) {
                $query->whereDate('time_in', now()->format('Y-m-d'));
            }]);
        }

        return $query;
    }

    public function filters(): array
    {
        return [

            DateFilter::make('Attendance Date', 'attendance-date')
                ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
                ->setFilterDefaultValue($this->attendanceDate)
                ->config([
                    'min' => (function () {
                        $minDate = $this->oldestDate;

                        return $minDate ? Carbon::parse($minDate)->format('Y-m-d') : now()->format('Y-m-d');
                    })(),
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                    'placeholder' => 'Enter Attendance Date',
                ])
                ->filter(function (Builder $builder, $value) {
                    $this->attendanceDate = $value;

                    return $builder->with(['attendances' => function ($builder) use ($value) {
                        $builder->whereDate('time_in', $value);
                    }]);
                }),

            SelectFilter::make('Employment Status', 'emp-status')
                ->options($this->employmentStatuses)
                ->filter(function (Builder $builder, string $value) {
                    return $query = $builder->where('employment_statuses.emp_status_id', $value);
                }),

        ];
    }

    public function filterReset(Builder $builder)
    {
        // I think this is work around for unremovable filter so it will not throw error
        // because it will get all attendance of the employee
        $this->dispatch('setFilter', 'attendance-date', now()->format('Y-m-d'));
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
}

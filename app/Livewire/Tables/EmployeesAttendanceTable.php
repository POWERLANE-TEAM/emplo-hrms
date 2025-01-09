<?php

namespace App\Livewire\Tables;

use App\Enums\BiometricPunchType;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\JobTitle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\ComponentAttributeBag;
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

    const NULL_TIME = '--';

    public function boot(): void
    {
        // dd();
    }

    #[Computed]
    public function getJobTitles(): Collection
    {
        return JobTitle::select('job_title_id', 'job_title')->orderBy('job_title', 'ASC')->get();
    }

    #[Computed]
    public function getJobTitlesOptions(): array
    {
        return $this->getJobTitles()->mapWithKeys(function ($jobTitle) {
            return [$jobTitle->job_title_id => $jobTitle->job_title];
        })
            ->prepend('Select All Job Positions', '')
            ->toArray();
    }



    #[Computed]
    public function getEmployeeStatus(): Collection
    {
        return EmploymentStatus::select('emp_status_id', 'emp_status_name')->orderBy('emp_status_name', 'ASC')->get();
    }

    #[Computed]
    public function getEmployeeStatusOptions(): array
    {
        return $this->getEmployeeStatus()->mapWithKeys(function ($employmentStatus) {
            return [$employmentStatus->emp_status_id => $employmentStatus->emp_status_name];
        })
            ->prepend('Select All Employee Status', '')
            ->toArray();
    }

    #[Computed]
    public function getOldestAttendace()
    {
        return Employee::with('attendanceLogs')
            ->get()
            ->pluck('attendanceLogs')
            ->flatten()
            ->min('timestamp');
    }

    private function getTimestamp($row, $type)
    {
        return optional($row->attendanceLogs->where('type', $type)->first())->timestamp;
    }

    private function getTimestampF($row, $type)
    {
        $timestamp = $this->getTimestamp($row, $type);
        return $timestamp ? Carbon::parse($timestamp)->format('g:i A') : self::NULL_TIME;
    }

    private function NOT_SET($property)
    {
        return "{$property} not set.";
    }

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

        $this->jobTitles = $this->getJobTitlesOptions();

        // $this->employmentStatuses = $this->getEmployeeStatusOptions();

        // $this->oldestDate = $this->getOldestAttendace();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Full Name'))
                ->label(fn($row) => $row->fullname)
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),

            Column::make(__('Time In'))
                ->label(fn($row) => $this->getTimestampF($row, BiometricPunchType::CHECK_IN->value)),

            Column::make(__('Time Out'))
                ->label(fn($row) => $this->getTimestampF($row, BiometricPunchType::CHECK_OUT->value)),

            Column::make(__('Overtime Time In'))
                ->label(fn($row) => $this->getTimestampF($row, BiometricPunchType::OVERTIME_IN->value))
                ->deselected(),

            Column::make(__('Overtime Time Out'))
                ->label(fn($row) => $this->getTimestampF($row, BiometricPunchType::OVERTIME_OUT->value))
                ->deselected(),

            Column::make(__('Arrival'))
                ->label(function ($row) {
                    $attendance = $this->getTimestamp($row, BiometricPunchType::CHECK_IN->value);

                    if (!$row->shift) return  $this->NOT_SET('shift');

                    $shiftStartTime = Carbon::parse($row->shift->start_time);
                    $now = Carbon::now();

                    if (is_null($attendance)) return $now->lessThan($shiftStartTime) ? 'arriving' : 'running late';
                    else {
                        $attendanceTime = Carbon::parse($attendance);
                        return $attendanceTime->lessThan($shiftStartTime) ? 'on time' : 'late';
                    }
                }),

            Column::make(__('Status'))
                ->label(function ($row) {
                    // $attendance = optional($row->attendanceLogs->first());
                    $timeIn = $this->getTimestamp($row, BiometricPunchType::CHECK_IN->value);
                    $timeOut = $this->getTimestamp($row, BiometricPunchType::CHECK_OUT->value);
                    $overtimeTimeIn = $this->getTimestamp($row, BiometricPunchType::OVERTIME_IN->value);

                    if (!$row->shift) return  $this->NOT_SET('shift');

                    $shiftEndTime = Carbon::parse($row->shift->end_time);
                    $now = Carbon::now();

                    // Temporary
                    if ($overtimeTimeIn) {
                        return 'overtime';
                    } else
                    if (is_null($timeOut)) {
                        if ($now->lessThan($shiftEndTime)) {
                            return 'on duty';
                        } elseif ($now->diffInMinutes($shiftEndTime) <= 30) {
                            return 'duty ended';
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
                ->label(function ($row) {
                    if (!$row->shift) return  $this->NOT_SET('shift');
                    return $row->shift->shift_name;
                })
                ->deselected(),

            Column::make('Job Title')
                ->label(function ($row) {
                    $row->load('jobTitle');
                    if (!$row->jobTitle) return  $this->NOT_SET('job level');
                    return $row->jobTitle->job_title;
                })

                ->deselected(),

            Column::make('Department')
                ->label(function ($row) {
                    $row->load('jobTitle.department');
                    if (!$row->jobTitle) return  $this->NOT_SET('job title');
                    if (!$row->jobTitle->department) return  $this->NOT_SET('department');
                    return $row->jobTitle->department->department_name;
                })
                ->deselected(),

            Column::make('Employment Status')
                ->label(function ($row) {
                    $row->load('status');
                    if (!$row->status) return  $this->NOT_SET('employment status');
                    return $row->status->emp_status_name;
                })
                ->deselected(),

            Column::make('Hired Date')
                ->label(function ($row) {
                    $row->load('application');

                    if (!$row->application) return  'No application';

                    return Carbon::parse($row->application->hired_at)->format('F j, Y') ?? report('Employee has no hired date.');
                })
                ->deselected(),

        ];
    }

    public function builder(): Builder
    {
        $query = Employee::query()->with(['attendanceLogs', 'specificArea', 'shift' /* 'status', */ /* 'jobTitle.department' */ /* , 'jobTitle.jobLevel' *//* , 'application' */])

            // prevent duplicate rows (workaround)
            ->leftJoin('employee_job_details', 'employees.employee_id', '=', 'employee_job_details.employee_id')
            ->leftJoin('specific_areas', 'employee_job_details.area_id', '=', 'specific_areas.area_id')
            ->distinct('employees.employee_id');

        // Maybe add specific area restriction based on permission?

        $this->limitSpecificArea($query);

        if (! $this->getAppliedFilterWithValue('attendance-date')) {
            $query->with(['attendanceLogs' => function ($query) {
                $query->whereDate('timestamp', now()->format('Y-m-d'));
            }]);
        }

        // dd($query->has('attendanceLogs')->limit(5)->get());

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
                        $minDate = $this->getOldestAttendace;

                        return $minDate ? Carbon::parse($minDate)->format('Y-m-d') : now()->format('Y-m-d');
                    })(),
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                    'placeholder' => 'Enter Attendance Date',
                ])
                ->filter(function (Builder $builder, $value) {
                    $this->attendanceDate = $value;

                    $builder->with(['attendanceLogs' => function ($query) use ($value) {
                        $query->whereDate('timestamp', $value);
                    }]);
                }),

            SelectFilter::make('Employment Status', 'emp-status')
                ->options($this->getEmployeeStatusOptions)
                ->filter(function (Builder $builder, string $value) {
                    // return $query = $builder->with('status')->where('emp_status_id', $value);
                    $builder->whereHas('status', function ($query) use ($value) {
                        $query->where('employment_statuses.emp_status_id', $value);
                    });
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
}

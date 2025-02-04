<?php

namespace App\Livewire\Tables;

use App\Models\Employee;
use App\Models\JobTitle;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use App\Enums\BiometricPunchType;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\Database\Eloquent\Collection;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use App\Models\EmploymentStatus as EmploymentStatusModel;
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
        return EmploymentStatusModel::select('emp_status_id', 'emp_status_name')->orderBy('emp_status_name', 'ASC')->get();
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
        $this->enableAllEvents();

        $this->configuringStandardTableMethods();
        
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {

            if (in_array($columnIndex, [3, 4])) {
                // Arrival and Status column
                return [
                    'class' => 'text-md-center text-capitalize',
                ];
            }

            return [
                'class' =>  $column->getTitle() === 'Employee' ? 'text-md-start' : 'text-md-center',
            ];
        });

        $this->attendanceDate = Carbon::now()->format('Y-m-d');

        // $this->setConfigurableAreas([
        //     'toolbar-left-start' => [
        //         'components.headings.main-heading',
        //         [
        //             'overrideClass' => true,
        //             'overrideContainerClass' => true,
        //             'attributes' => new ComponentAttributeBag(['class' => 'fs-4 fw-bold']),
        //             'heading' => Carbon::parse($this->attendanceDate)->format('F j, Y'),
        //         ],
        //     ],
        // ]);

        $this->jobTitles = $this->getJobTitlesOptions();

        // $this->employmentStatuses = $this->getEmployeeStatusOptions();

        // $this->oldestDate = $this->getOldestAttendace();
    }

    #[On('syncToDtrTable')]
    public function refreshDatatable()
    {
        $this->dispatch('refreshDatatable');
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->addSelect([
                'latest_timestamp' => AttendanceLog::select('timestamp')
                    ->whereColumn('attendance_logs.employee_id', 'employees.employee_id')
                    ->latest('timestamp')
                    ->take(1),
            ])
            ->orderBy('latest_timestamp', 'desc')
            ->with([
                'jobDetail',
                'account',
                'status',
                'specificArea',
                'shift',
                'jobTitle' => [
                    'jobFamily',
                    'jobLevel',
                ],
            ])
            ->activeEmploymentStatus();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->full_name);
                    $photo = $row->account->photo;
                    $id = $row->employee_id;
                    $status = $row->status->emp_status_name;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                    <small class="text-muted">' . e($status) . '</small>
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
                }),

            Column::make(__('Check In'))
                ->label(fn($row) => $this->getTimestampF($row, BiometricPunchType::CHECK_IN->value)),

            Column::make(__('Check Out'))
                ->label(fn($row) => $this->getTimestampF($row, BiometricPunchType::CHECK_OUT->value)),

            // Column::make(__('Date'))
            //     ->label(fn ($row) => $row ? Carbon::parse($row->attendanceLogs->timestamp)->format('F d, Y') : self::NULL_TIME),

            /**
             * |--------------------------------------------------------------------------
             * | Start of Additional Columns
             * |--------------------------------------------------------------------------
             * Description
             */
            Column::make('Shift')
                ->label(function ($row) {
                    return $row->shift->shift_name;
                }),

            Column::make('Schedule')
                ->label(fn ($row) => $row->shift_schedule),

            Column::make('Job Title')
                ->label(function ($row) {
                    if (!$row->jobTitle) return  $this->NOT_SET('job level');
                    return $row->jobTitle->job_title;
                }),

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

    public function filters(): array
    {
        return [
            DateFilter::make('Attendance Date', 'attendance-date')
                ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
                ->config([
                    'min' => (function () {
                        $minDate = $this->getOldestAttendace;

                        return $minDate ? Carbon::make($minDate)->format('Y-m-d') : now()->format('Y-m-d');
                    })(),
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                    'placeholder' => 'Enter Attendance Date',
                ])
                ->filter(function (Builder $query, $value) {
                    $query->with(['attendanceLogs' => function ($query) use ($value) {
                        $query->whereDate('timestamp', $value);
                    }]);
                    // $query->whereHas('attendanceLogs', function ($subquery) use ($value) {
                    //     $subquery->whereDate('timestamp', $value);
                    // });
                })
                ->setFilterDefaultValue(now()->toDateString()),

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
}

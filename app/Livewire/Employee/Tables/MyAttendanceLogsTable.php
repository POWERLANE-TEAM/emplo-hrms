<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\BiometricPunchType;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MyAttendanceLogsTable extends DataTableComponent
{
    protected $model = AttendanceLog::class;

    #[Reactive]
    #[Locked]
    public $period;

    public Employee $employee;

    public function configure(): void
    {
        $this->setPrimaryKey('uid');
        $this->setPageName('my-attendance-logs');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('timestamp', 'desc');
        $this->setSearchDebounce(1000);
        $this->setTrimSearchStringEnabled();
        $this->setEmptyMessage(__('No results found.'));
        $this->setPerPageAccepted([10, 25, 50, 100, -1]);
        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);
        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-2']);
        $this->setRememberColumnSelectionDisabled();

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
        ]);

        $this->setThAttributes(function (Column $column) {
            return [
                'default' => true,
                'class' => 'text-center fw-medium',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'text-md-center',
            ];
        });

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-5 py-1 text-secondary-emphasis fw-semibold text-underline',
                    ]),
                    'heading' => __('Attendance Timesheet'),
                ],
            ],
        ]);
    }

    public function builder(): Builder
    {
        $period = Payroll::find($this->period);

        return AttendanceLog::query()
            ->where('employee_id', $this->employee->employee_id)
            ->whereBetween('timestamp', [$period->cut_off_start, $period->cut_off_end])
            ->select('*');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Workday Date'), 'timestamp')
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => Carbon::make($row->timestamp)->format('F d, Y')),

            Column::make(__('Type'))
                ->label(fn ($row) => BiometricPunchType::from($row->type)->getDescription()),

            Column::make(__('Time'), 'timestamp')
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => Carbon::make($row->timestamp)->format('g:i A')),
        ];
    }
}

<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Employee;
use App\Models\Overtime;
use App\Enums\StatusBadge;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use App\Livewire\Employee\Overtimes\CutOffPayoutPeriodsApproval;

class EmployeeOvertimeRequestSummariesTable extends DataTableComponent
{
    protected $model = Overtime::class;

    public Employee $employee;

    public function configure(): void
    {
        $this->setPrimaryKey('overtime_id');
        $this->setPageName('overtime-summmary');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('filed_at', 'desc');
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
            $eventPayload = $this->createEventPayload($row);

            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
                'role' => 'button',
                'wire:click' => "\$dispatchTo(
                    'employee.overtimes.individual-overtime-request-approval',
                    'showOvertimeRequestApprovalInfo',
                    { eventPayload: ".json_encode($eventPayload)."})",
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-3 search text-body body-bg shadow-sm',
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

        // $this->setConfigurableAreas([
        //     'toolbar-left-start' => [
        //         'components.headings.main-heading',
        //         [
        //             'overrideClass' => true,
        //             'overrideContainerClass' => true,
        //             'attributes' => new ComponentAttributeBag([
        //                 'class' => 'fs-5 py-1 text-secondary-emphasis fw-medium text-underline',
        //             ]),
        //             'heading' => __('Some text here'),
        //         ],
        //     ],
        // ]);
    }

    private function createEventPayload($row)
    {
        return [
            'requestor' => [
                'name'              => $row->employee->full_name,
                'photo'             => $row->employee->account->photo,
                'job_title'         => $row->employee->jobTitle->job_title,
                'job_level'         => $row->employee->jobTitle->jobLevel->job_level,
                'job_level_name'    => $row->employee->jobTitle->jobLevel->job_level_name,
                'employee_id'       => $row->employee->employee_id,
                'shift'             => $row->employee->shift->shift_name,
                'shift_schedule'    => $row->employee->shift_schedule,
                'employment'        => $row->employee->status->emp_status_name,
            ],
            'overtime_details' => [
                'work_performed'        => $row->work_performed,
                'start_time'            => $row->start_time->format('F d, Y g:i A'),
                'end_time'              => $row->end_time->format('F d, Y g:i A'),
                'hours_requested'       => $row->hours_requested,
                'authorizer_signed_at'  => $row->authorizer_signed_at?->format('F d, Y g:i A'),
                'authorizer'            => $row?->authorizedBy?->full_name,
                'denied_at'             => $row->denied_at?->format('F d, Y g:i A'),
                'denier'                => $row?->deniedBy?->full_name,
                'feedback'              => $row->feedback,
                'filed_at'              => $row->filed_at->format('F d, Y g:i A'),
                'modified_at'           => $row->modified_at->format('F d, Y g:i A'),         
            ],
        ];
    }

    private function getPayrollOptions()
    {
        return Overtime::query()
            ->with([
                'payrollApproval.payroll'
            ])
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->payrollApproval->payroll->payroll_id => $item->payrollApproval->payroll->cut_off];
            })
            ->toArray();
    }

    public function builder(): Builder
    {
        return Overtime::query()
            ->with([
                'authorizedBy',
                'deniedBy',
                'employee',
                'employee' => [
                    'account',
                    'status',
                    'shift',
                    'jobTitle' => [
                        'jobLevel',
                        'jobFamily',
                    ],
                ],
            ])
            ->where('employee_id', $this->employee->employee_id)
            ->select('*');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Work Performed'))
                ->sortable(),

            Column::make(__('Total Hours'))
                ->label(fn ($row) => $row->hoursRequested)
                ->deselected(),

            Column::make(__('Start Time'))
                ->format(fn ($row) => $row->format('F d, Y g:i A'))
                ->sortable(),

            Column::make(__('End Time'))
                ->format(fn ($row) => $row->format('F d, Y g:i A'))
                ->sortable(),

            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at->format('F d, Y g:i A'))
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('filed_at', $direction))
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Authorization'))
                ->label(function ($row) {
                    $badge = [
                        'color' => StatusBadge::PENDING->getColor(),
                        'slot' => StatusBadge::PENDING->getLabel(),
                    ];

                    if ($row->authorizer_signed_at) {
                        return $row->authorizedBy->full_name;
                    } elseif ($row->denied_at) {
                        $badge = [
                            'color' => StatusBadge::DENIED->getColor(),
                            'slot' => StatusBadge::DENIED->getLabel(),
                        ];
                    }

                    return view('components.status-badge')->with($badge);
                }),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Payroll'), 'payroll')
                ->options($this->getPayrollOptions())
                ->setFilterPillTitle('Payroll')
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('payrollApproval.payroll', function ($subquery) use ($value) {
                        $subquery->where('payroll_id', $value);
                    });

                    $this->dispatch('payrollDateModified', $value)->to(CutOffPayoutPeriodsApproval::class);
                })
        ];
    }
}

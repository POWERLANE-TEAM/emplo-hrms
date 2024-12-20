<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\Payroll;
use App\Models\Overtime;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Enums\OvertimeRequestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class OvertimeRequestsTable extends DataTableComponent
{
    protected $model = Overtime::class;

    public function configure(): void
    {
        $this->setPrimaryKey('overtime_id');
        $this->enableFilterAppliedEvent();
        $this->setPageName('overtime-requests');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->enableAllEvents();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('filed_at', 'desc');
        $this->setSearchDebounce(1000);
        $this->setTrimSearchStringEnabled();
        $this->setEmptyMessage(__('No results found.'));
        $this->setPerPageAccepted([10, 25, 50, 100, -1]);
        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);
        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-2']);

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
                'role' => 'button',
                'wire:click' => "\$dispatchTo(
                    'employee.overtimes.request-overtime-approval', 
                    'showOvertimeRequestApproval', 
                    { overtimeId: $row->overtime_id })",
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-3 search text-body body-bg shadow-sm',
        ]);

        $this->setThAttributes(function (Column $column) {
            return [
                'default' => true,
                'class' => 'fw-medium',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'text-md-start',
            ];
        });

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-6 py-1 text-secondary-emphasis fw-medium text-underline',
                    ]),
                    'heading' => __('Filters are automatically applied upon page access.'),
                ],
            ],
        ]);
    }

    public function builder(): Builder
    {
        return Overtime::query()
            ->with([
                'payrollApproval.payroll',
                'employee',
                'employee.account',
                'employee.jobTitle.jobFamily',
            ])
            ->whereHas('employee.jobTitle.jobFamily', function ($query) {
                $query->where('job_family_id', Auth::user()->account->jobTitle->jobFamily->job_family_id);
            })
            ->whereNot('employee_id', Auth::user()->account->employee_id)
            ->select('*');
    }

    #[On('changesSaved')]
    public function refresh()
    {
        $this->dispatch('refreshDatatable');
    }
    
    private function getDefaultCutOff()
    {
        return Payroll::getCutOffPeriod(now());
    }

    private function getEmployeeOptions()
    {
        return Overtime::query()
            ->with('employee')
            ->whereNot('employee_id', Auth::user()->account->employee_id)
            ->select('employee_id')
            ->distinct()
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->employee->employee_id => $item->employee->full_name];
            })
            ->toArray();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->employee->full_name);
                    $photo = $row->employee->account->photo;
                    $id = $row->employee->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('employee.account', function ($subquery) use ($direction) {
                        $subquery->orderBy('last_name', $direction);
                    });
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('employee.account', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                }),

            Column::make(__('Work Performed'))
                ->searchable()
                ->deselected(),

            Column::make(__('Start Time'))
                ->sortable()
                ->deselected(),

            Column::make(__('End Time'))
                ->sortable()
                ->deselected(),
            
            Column::make(__('Date Requested'), 'date')
                ->format(fn ($row) => Carbon::parse($row)->format('F d, Y'))
                ->sortable()
                ->searchable()
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Request Date')),
            
            Column::make(__('Hours Requested'))
                ->label(fn ($row) => $row->hoursRequested)
                ->sortable(function (Builder $query, $direction) {
                    return $query->selectRaw('abs(extract(epoch from (start_time - end_time))) / 60 as time_diff')
                                 ->orderBy('time_diff', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Status'))
                ->label(function ($row) {
                    if ($row->authorizer_signed_at) {
                        return __('Approved');
                    } elseif ($row->denied_at) {
                        return __('Denied');
                    } else {
                        return __('Pending');
                    }
                }),

            Column::make(__('Cut-Off Period'))
                ->label(fn ($row) => $row->payrollApproval->payroll->cut_off)
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('date', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Cut-Off')),

            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),
        ];
    }

    public function filters(): array
    {
        return [            
            DateRangeFilter::make(__('Cut-Off Period'))
                ->config([
                    'allowInput' => true,
                    'altFormat' => 'F j, Y',
                    'ariaDateFormat' => 'F j, Y',
                    'dateFormat' => 'Y-m-d',
                    // 'latestDate' => now(),
                    'locale' => 'en',
                ])
                ->setFilterPillTitle('Cut-off')
                ->filter(function (Builder $query, $value) {
                    $startDate = Carbon::parse($value['minDate']);
                    $endDate = Carbon::parse($value['maxDate']);

                    return $query->whereHas('payrollApproval.payroll', function ($subquery) use ($startDate, $endDate) {
                        $subquery->whereBetween('cut_off_start', [$startDate, $endDate])
                            ->orWhereBetween('cut_off_end', [$startDate, $endDate]);
                    });
                })
                ->setFilterDefaultValue([
                    'minDate' => $this->getDefaultCutOff()['start'],
                    'maxDate' => $this->getDefaultCutOff()['end'],
                ]),

            SelectFilter::make(__('Employee'))
                ->options($this->getEmployeeOptions())
                ->filter(function (Builder $query, $value) {
                    return $query->whereHas('employee', function ($subquery) use ($value) {
                        $subquery->where('employee_id', $value);
                    });
                }),

            SelectFilter::make(__('Request Status'))
                ->options(
                    array_reduce(
                        OvertimeRequestStatus::cases(),
                        fn ($options, $case) => $options + [$case->value => $case->getLabel()],
                        []
                    )
                )
                ->setFilterPillTitle('Status')
                ->filter(function (Builder $query, $value) {
                    if ($value === OvertimeRequestStatus::APPROVED->value) {
                        $query->whereNotNull('authorizer_signed_at');
                    } elseif ($value === OvertimeRequestStatus::PENDING->value) {
                        $query->whereNull('authorizer_signed_at')
                            ->whereNull('denied_at');
                    } elseif ($value === OvertimeRequestStatus::DENIED->value) {
                        $query->whereNotNull('denied_at');
                    }
                })
                ->setFilterDefaultValue(OvertimeRequestStatus::PENDING->value)
        ];        
    }
}

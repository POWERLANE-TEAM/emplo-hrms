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
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class AllOvertimeRequestsTable extends DataTableComponent
{
    protected $model = Overtime::class;

    public function configure(): void
    {
        $this->setPrimaryKey('overtime_id');
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
        $this->setRememberColumnSelectionDisabled();

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
                    'employee.overtimes.secondary-overtime-request-approval', 
                    'showPreApprovedOvertimeRequestApproval', 
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
    }

    public function builder(): Builder
    {
        return Overtime::query()
            ->with([
                'employee',
                'employee.account',
                'employee.jobTitle.jobFamily',
                'processes',
                'processes.initialApprover',
            ])
            ->select('*')
            ->whereNot('employee_id', Auth::user()->account->employee_id)
            ->whereHas('processes', function ($subquery) {
                $subquery->whereNotNull('initial_approver_signed_at');
            });
    }

    #[On('changesSaved')]
    public function refresh()
    {
        $this->dispatch('refreshDatatable');
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
                    return $query->whereHas('employee', function ($subquery) use ($direction) {
                        $subquery->orderBy('last_name', $direction);
                    });
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('employee', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                }),

            Column::make(__('Job Family'))
                ->label(function ($row) {
                    return $row->employee->jobTitle->jobFamily->job_family_name;
                })
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('employee.jobTitle.jobFamily', function ($subquery) use ($direction) {
                        $subquery->orderBy('job_family_name', $direction);
                    });
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('employee.jobTitle.jobFamily', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('job_family_name', "%{$searchTerm}%");
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
                ->label(fn ($row) => $row->getHoursRequested())
                ->sortable(function (Builder $query, $direction) {
                    return $query->selectRaw('abs(extract(epoch from (start_time - end_time))) / 60 as time_diff')
                                 ->orderBy('time_diff', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Status'))
                ->label(function ($row) {
                    if ($row->processes->first()->secondary_approver_signed_at) {
                        return __('Approved');
                    } elseif ($row->processes->first()->denied_at) {
                        return __('Denied');
                    } else {
                        return __('Pending');
                    }
                }),

            Column::make(__('Cut-Off Period'))
                ->label(function ($row) {
                    $cutOff = Payroll::getCutOffPeriod($row->date, isReadableFormat: true);
                    return $cutOff['start']. ' - ' .$cutOff['end'];
                })
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

            Column::make(__('Initial Approval'))
                ->label(function ($row) {
                    return $row->processes->first()->initialApprover->full_name;
                })
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('processes.initialApprover', function ($subquery) use ($direction) {
                        $subquery->orderBy('last_name', $direction);
                    });
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('processes.initialApprover', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                })
                ->deselected(),
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
                    'latestDate' => now(),
                    'placeholder' => 'Enter Date Range',
                    'locale' => 'en',
                ])
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $query, $value) {
                    if (isset($value['minDate'], $value['maxDate'])) {
                        $startDate = Carbon::parse($value['minDate']);
                        $endDate = Carbon::parse($value['maxDate']);
        
                        $cutOffPeriod = Payroll::getCutOffPeriodForDate($startDate);
        
                        return $query->where('cut_off', $cutOffPeriod->value)
                            ->whereBetween('date', [$startDate, $endDate]);
                    }
        
                    return $query;
                }),

            SelectFilter::make(__('Request Status'))
                ->options(
                    array_reduce(
                        OvertimeRequestStatus::cases(),
                        fn ($options, $case) => $options + [$case->value => $case->getLabel()],
                        []
                    )
                )
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('processes', function ($subquery) use ($value) {
                        if ($value === OvertimeRequestStatus::APPROVED->value) {
                            $subquery->whereNotNull('secondary_approver_signed_at');
                        } elseif ($value === OvertimeRequestStatus::PENDING->value) {
                            $subquery->whereNull('secondary_approver_signed_at')
                                ->whereNull('denied_at');
                        } elseif ($value === OvertimeRequestStatus::DENIED->value) {
                            $subquery->whereNotNull('denied_at');
                        }
                    });
                })
                ->setFilterDefaultValue(OvertimeRequestStatus::PENDING->value)
        ];        
    }
}

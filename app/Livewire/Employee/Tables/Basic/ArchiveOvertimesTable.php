<?php

namespace App\Livewire\Employee\Tables\Basic;

use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Enums\OvertimeRequestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ArchiveOvertimesTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('overtime_id');
        $this->setPageName('overtime-requests');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
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
                    'employee.overtimes.basic.edit-overtime-request', 
                    'showOvertimeRequest', 
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
                'class' => 'text-center fw-medium',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return Overtime::query()
            ->with('employee')
            ->select('*')
            ->where('employee_id', Auth::user()->account->employee_id);
    }
    
    #[On('changesSaved')]
    public function refreshComponent()
    {
        $this->dispatch('refreshDatatable');
    }

    private function getPayrollOptions()
    {
        return Overtime::query()
            ->with('payrollApproval.payroll')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->payrollApproval->payroll->payroll_id => $item->payrollApproval->payroll->cut_off];
            })
            ->toArray();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Date Requested'))
                ->label(fn ($row) => Carbon::make($row->date)->format('F d, Y'))
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('date', $direction);
                }),

            Column::make(__('Start Time'))
                ->sortable()
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('End Time'))
                ->sortable()
                ->setSortingPillDirections('Asc', 'Desc'),
            
            Column::make(__('Hours Requested'))
                ->label(fn ($row) => $row->hoursRequested),
            
            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Authorization'))
                ->label(function ($row) {
                    if ($row->authorizer_signed_at) {
                        return $row->authorizedBy->full_name;
                    } elseif ($row->denied_at) {
                        return __('Denied');
                    } else {
                        return __('Pending');
                    }
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
                }),

            DateFilter::make(__('Filing Date'))
                ->config([
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                ])
                ->filter(function (Builder $query, $value) {
                    return $query->whereDate('filed_at', $value);
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
                    $query->whereHas('approvals', function ($subquery) use ($value) {
                        if ($value === OvertimeRequestStatus::APPROVED->value) {
                            $subquery->whereNotNull('secondary_approver_signed_at');
                        } elseif ($value === OvertimeRequestStatus::PENDING->value) {
                            $subquery->whereNull('secondary_approver_signed_at');
                        } elseif ($value === OvertimeRequestStatus::DENIED->value) {
                            $subquery->whereNotNull('denied_at');
                        }
                    });
                })
        ];
    }
}

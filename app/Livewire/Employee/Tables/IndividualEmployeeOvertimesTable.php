<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class IndividualEmployeeOvertimesTable extends DataTableComponent
{
    protected $model = Overtime::class;

    public Employee $employee;

    public function configure(): void
    {
        $this->setPrimaryKey('overtime_id');
        $this->enableFilterAppliedEvent();
        $this->setPageName('employee-overtimes');
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
                // 'role' => 'button',
                // 'wire:click' => "\$dispatchTo(
                //     'employee.overtimes.request-overtime-approval',
                //     'showOvertimeRequestApproval',
                //     { overtimeId: $row->overtime_id })",
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
                'payrollApproval.payroll',
                'employee',
                'employee.account',
                'employee.jobTitle.jobFamily',
            ])
            ->whereHas('employee.jobTitle.jobFamily', function ($query) {
                $query->where('job_family_id', $this->employee->jobTitle->jobFamily->job_family_id);
            })
            ->whereNot('employee_id', $this->employee->employee_id)
            ->select('*');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Work Performed'))
                ->searchable()
                ->deselected(),

            Column::make(__('Start Time'))
                ->sortable()
                ->deselected(),

            Column::make(__('End Time'))
                ->sortable()
                ->deselected(),

            // Column::make(__('Date Requested'), 'date')
            //     ->format(fn ($row) => Carbon::parse($row)->format('F d, Y'))
            //     ->sortable()
            //     ->searchable()
            //     ->setSortingPillDirections('Asc', 'Desc')
            //     ->setSortingPillTitle(__('Request Date')),

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
}

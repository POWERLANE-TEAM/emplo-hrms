<?php

namespace App\Livewire\Employee\Tables;

use App\Livewire\Employee\Overtimes\CutOffPayoutPeriodsApproval;
use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class EmployeeOvertimeRequestSummariesTable extends DataTableComponent
{
    protected $model = Overtime::class;

    public Employee $employee;

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

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
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

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-5 py-1 text-secondary-emphasis fw-medium text-underline',
                    ]),
                    'heading' => __('Some text here'),
                ],
            ],
        ]);
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

    public function builder(): Builder
    {
        return Overtime::query()
            ->where('employee_id', $this->employee->employee_id)
            ->select('*');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Work Performed'))
                ->sortable(),

            Column::make(__('Date Requested'))
                ->label(fn ($row) => Carbon::make($row->date)->format('F d, Y'))
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('date', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Total Hours'))
                ->label(fn ($row) => $row->hoursRequested)
                ->deselected(),

            Column::make(__('Start Time'))
                ->sortable(),

            Column::make(__('End Time'))
                ->sortable(),

            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('filed_at', $direction))
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

                    $this->dispatch('payrollDateModified', $value)->to(CutOffPayoutPeriodsApproval::class);
                })
        ];
    }
}

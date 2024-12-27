<?php

namespace App\Livewire\Employee\Tables;

use App\Models\EmployeeLeave;
use App\Models\LeaveCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class MyLeavesTable extends DataTableComponent
{
    protected $model = EmployeeLeave::class;

    public function configure(): void
    {
        $routePrefix = Auth::user()->account_type;

        $this->setPrimaryKey('emp_leave_id')
            ->setTableRowUrl(fn ($row) => route("{$routePrefix}.leaves.show", [
                'leave' => $row->emp_leave_id
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('my_leave_requests');
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
                        'class' => 'fs-4 text-secondary-emphasis fw-semibold',
                    ]),
                    'heading' => __('Leave Requests'),
                ],
            ],
        ]);
    }

    private function getLeaveTypeOptions()
    {
        return LeaveCategory::all()
            ->mapWithKeys(fn ($item) => [$item->leave_category_id => $item->leave_category_name])
            ->toArray();
    }

    public function builder(): Builder
    {
        return EmployeeLeave::query()
            ->with(['category'])
            ->where('employee_id', Auth::user()->account->employee_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Leave Type'))
                ->label(fn ($row) => $row->category->leave_category_name)
                ->sortable(function (Builder $query, $direction) {
                    $query->whereHas('category', fn ($subquery) => $subquery->orderBy('leave_category_name', $direction));
                }),

            Column::make(__('Reason'))
                ->label(fn ($row) => $row->reason)
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('reason', "%{$searchTerm}%"))
                ->deselected(),

            Column::make(__('Start Date'))
                ->label(fn ($row) => $row->start_date)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('start_date', $direction)),

            Column::make(__('End Date'))
                ->label(fn ($row) => $row->end_date)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('end_date', $direction)),

            Column::make(__('Status'))
                ->label(function ($row) {
                    if ($row->fourth_approver_signed_at) {
                        return __('Approved');
                    } elseif ($row->denied_at) {
                        return __('Denied');
                    } else {
                        return __('Pending');
                    }
                }),

            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('filed_at', $direction)),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Leave Type'))
                ->options($this->getLeaveTypeOptions())
                ->filter(fn (Builder $query, $value) => $query->where('leave_category_id', $value)),
        ];
    }
}

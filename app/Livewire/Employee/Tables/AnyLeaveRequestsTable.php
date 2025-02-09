<?php

namespace App\Livewire\Employee\Tables;

use Illuminate\Support\Str;
use App\Models\EmployeeLeave;
use App\Models\LeaveCategory;
use Livewire\Attributes\Locked;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyLeaveRequestsTable extends DataTableComponent
{
    protected $model = EmployeeLeave::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('emp_leave_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.leaves.employee.requests", [
                'leave' => $row->emp_leave_id
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('leaves');
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
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start' : 'text-md-center',
            ];
        });
    }

    private function getLeaveStatus($row)
    {
        if ($row->fourth_approver_signed_at) {
            return __('Approved');
        } elseif ($row->denied_at) {
            return __('Denied');
        } else {
            return __('Pending');
        }
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
            ->with([
                'employee',
                'category',
                'employee.jobTitle.jobFamily',
                'initialApprover',
            ])
            ->whereNotNull('third_approver_signed_at')
            ->orWhereNotNull('secondary_approver_signed_at');
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
                    return $query->join('employees', 'employees.employee_id', '=', 'employee_leaves.employee_id')
                        ->orderBy('employees.last_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('employee', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                }),

            Column::make(__('Job Family'))
                ->label(fn ($row) => $row->employee->jobTitle->jobFamily->job_family_name)
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('employee.jobTitle.jobFamily', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('job_family_name', "%{$searchTerm}%");
                    });
                }),

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
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('start_date', $direction))
                ->deselected(),

            Column::make(__('End Date'))
                ->label(fn ($row) => $row->end_date)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('end_date', $direction))
                ->deselected(),

            Column::make('Status')
                ->label(fn ($row) => $this->getLeaveStatus($row)),

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

            SelectFilter::make(__('Status'))
                ->options([
                    '1' => __('Pending'),
                    '2' => __('Approved'),
                    '3' => __('Denied'),
                ])
                ->filter(function (Builder $query, $value) {
                    if ($value === '1') {
                        $query->where(function ($subQuery) {
                            $subQuery->whereNotNull('third_approver_signed_at')
                                     ->orWhereNotNull('secondary_approver_signed_at');
                        })
                        ->whereNull('denied_at');
                    } elseif ($value === '2' ) {
                        $query->whereNotNull('fourth_approver_signed_at');
                    } elseif ($value === '3') {
                        $query->whereNotNull('denied_at');
                    }
                })
                ->setFilterDefaultValue('1')
        ];
    }
}

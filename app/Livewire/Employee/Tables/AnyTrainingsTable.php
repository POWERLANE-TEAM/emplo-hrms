<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Employee;
use Illuminate\Support\Str;
use App\Enums\EmploymentStatus;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyTrainingsTable extends DataTableComponent
{
    protected $model = Employee::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.trainings.employee", [
                'employee' => $row->employee_id
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('trainings');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        // $this->setDefaultSort('filed_at', 'desc');
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
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start' : 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->with([
                'account',
                'status' => fn ($query) => $query->select('emp_status_name'),
                'jobTitle',
                'jobTitle.jobFamily',
                'trainings',
            ])
            ->whereHas('status', function ($query) {
                $query->where('emp_status_name', EmploymentStatus::REGULAR->label())
                    ->orWhere('emp_status_name', EmploymentStatus::PROBATIONARY->label());
            });
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

            Column::make(__('Job Title'))
                ->label(fn ($row) => $row->jobTitle->job_title),

            Column::make(__('Job Family'))
                ->label(fn ($row) => $row->jobTitle->jobFamily->job_family_name),

            Column::make(__('Training Count'))
                ->label(fn ($row) => $row->trainings->count())
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Employment'))
                ->options($this->getEmploymentStatusOptions())
                ->filter(function (Builder $query, $value) {
                    return $query->whereHas('status', function ($subQuery) use ($value) {
                        $subQuery->where('emp_status_name', $value);
                    });
                })
                ->setFilterDefaultValue(EmploymentStatus::REGULAR->label()),
        ];
    }

    private function getEmploymentStatusOptions()
    {
        return array_reduce(EmploymentStatus::cases(),
            fn ($options, $case) => $options + [$case->label() => $case->label()],
            []
        );
    }
}

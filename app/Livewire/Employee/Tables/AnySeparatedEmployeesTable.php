<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnySeparatedEmployeesTable extends DataTableComponent
{
    protected $model = Employee::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.archives.employee", [
                'employee' => $row,
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('any-separated-employee');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('updated_at', 'desc');
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

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-5 py-1 fw-semibold text-underline',
                    ]),
                    'heading' => __('Archived Employee 201 Files'),
                ],
            ],
        ]);
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->with([
                'account',
                'status',
                'jobTitle',
                'lifecycle',
            ])
            ->whereHas('status', function ($query) {
                $query->whereNotIn('emp_status_name', [
                    EmploymentStatus::PROBATIONARY->label(),
                    EmploymentStatus::REGULAR->label(),
                ]);
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

            Column::make(__('Separation Type'))
                ->label(fn ($row) => $row->status->emp_status_name),

            Column::make(__('Separated On'))
                ->label(fn ($row) => Carbon::make($row->lifecycle->separated_at)->format('F d, Y')),

            Column::make(__('Date Until Data Disposal'))
                ->label(function ($row) {
                        $separationDate = Carbon::parse($row->lifecycle->separated_at);
                        $retentionDate = EmploymentStatus::separatedEmployeeDataRetentionPeriod($separationDate);
                        
                        return now()->diff($retentionDate)->format('%y years %m months %d days');
                    }
                )
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Separation Type'))
                ->options($this->getSeparationTypeOptions())
                ->filter(function (Builder $query, $value) {
                    return $query->whereHas('status', function ($subquery) use ($value) {
                        $subquery->where('emp_status_name', $value);
                    });
                }),
        ];
    }

    private function getSeparationTypeOptions()
    {
        $all = array_reduce(EmploymentStatus::cases(), function ($carry, $case) {
            $carry[$case->label()] = $case->label();
            return $carry;
        }, []);
        
        return array_filter($all, fn ($item) => ! in_array($item, [
            EmploymentStatus::PROBATIONARY->label(),
            EmploymentStatus::REGULAR->label(),
        ]));
    }
}

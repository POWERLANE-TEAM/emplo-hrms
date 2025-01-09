<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use App\Models\RegularPerformancePeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyRegularsPerformancesTable extends DataTableComponent
{
    protected $model = Employee::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.performances.regulars.review", [
                'performance' => $row->performancesAsRegular->first()->regular_performance_id,
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('any-regulars-performance');
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
                'class' => $column->getTitle() === 'Evaluatee' ? 'text-md-start' :'text-md-center',
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
                    'heading' => __('Current Period: ').now()->format('F d, Y'),
                ],
            ],
        ]);
    }
    
    public function builder(): Builder
    {
        return Employee::query()
            ->with([
                'account',
                'performancesAsRegular',
                'performancesAsRegular.period',
                'performancesAsRegular.categoryRatings.rating',
            ])
            ->whereNot('employee_id', Auth::user()->account->employee_id)
            ->whereHas('jobDetail.status', function ($query) {
                $query->where('emp_status_name', EmploymentStatus::REGULAR->label());
            })
            ->whereHas('performancesAsRegular', function ($query) {
                $query->whereNotNull('secondary_approver_signed_at');
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('Evaluatee'))
                ->label(function ($row) {
                    $name = Str::headline($row->full_name);
                    $photo = $row->account->photo;
                    $id = $row->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name' ,$direction))
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereLike('first_name', "%{$searchTerm}%")
                        ->orWhereLike('middle_name', "%{$searchTerm}%")
                        ->orWhereLike('last_name', "%{$searchTerm}%");
                }),
            
            Column::make(__('Status'))
                ->label(function ($row) {
                    if ($row->performancesAsRegular->first()) {
                        return __('Completed');
                    } else {
                        return __('Incomplete');
                    }
                }),

            Column::make(__('Results'))
                ->label(function ($row) {
                    if ($row->performancesAsRegular->first()) {
                        if ($row->performancesAsRegular->first()->fourth_approver_signed_at) {
                            return __('Approved');
                        } else {
                            return __('Pending');
                        }
                    } else {
                        return '-';
                    }
                }),

            Column::make(__('Final Rating'))
                ->label(function ($row) {
                    if ($row->performancesAsRegular->first()) {
                        $finalRating = $row->performancesAsRegular->first()->final_rating;  
                        return $finalRating['ratingAvg'];
                    } else {
                        return '-';
                    }
                }),

            Column::make(__('Performance Scale'))
                ->label(function ($row) {
                    if ($row->performancesAsRegular->first()) {
                        $finalRating = $row->performancesAsRegular->first()->final_rating;  
                        return $finalRating['performanceScale'];
                    } else {
                        return '-';
                    }
                }),

            Column::make(__('Period'))
                ->label(function ($row) {
                    if (is_null($row->performancesAsRegular->first())) {
                        return ' - ';
                    } else {
                        return $row->performancesAsRegular->first()->period->interval;
                    }
                }),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Evaluation Period'))
                ->options($this->getPeriodOptions())
                ->filter(function (Builder $query, $value) {
                    return $query->whereHas('performancesAsRegular.period', function ($subQuery) use ($value) {
                        $subQuery->where('period_id', $value);
                    });
                })
                ->setFilterPillTitle('Period')
                // ->setFilterDefaultValue(RegularPerformancePeriod::latest()->first()->period_id)
        ];
    }
    private function getPeriodOptions(): array
    {
        return RegularPerformancePeriod::all()
            ->mapWithKeys(function ($item) {
                $start = Carbon::make($item->start_date)->format('j M, Y');
                $end = Carbon::make($item->end_date)->format('j M Y');
                $period = "{$start} - {$end}";

                return [$item->period_id => $period];
            })->toArray();
    }
}

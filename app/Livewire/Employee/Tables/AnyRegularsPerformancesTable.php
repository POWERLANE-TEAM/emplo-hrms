<?php

namespace App\Livewire\Employee\Tables;

use App\Livewire\Tables\Defaults;
use App\Models\Employee;
use App\Enums\StatusBadge;
use App\Enums\EmploymentStatus;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use App\Models\RegularPerformancePeriod;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyRegularsPerformancesTable extends DataTableComponent
{
    use Defaults;

    protected $model = Employee::class;

    #[Locked]
    public $routePrefix;

    private $periodOptions;

    private $performance;

    private $period;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id');
        $this->setPageName('any-regulars-performance');
        $this->setDefaultSortingLabels('Lowest', 'Highest');
        $this->setSingleSortingDisabled();
        $this->setRememberColumnSelectionDisabled();
        $this->configuringStandardTableMethods();

        $this->periodOptions = $this->getPeriodOptions();
        
        $this->setTableAttributes([
            'default' => true,
            'class' => 'px-1 no-transition',
        ]);

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.table.filter.select-filter',
                [
                    'filter' => (function () {
                        return SelectFilter::make(__('Evaluation Period'), 'evalPeriod')
                            ->options($this->periodOptions)
                            ->filter(function (Builder $query, $value) {
                                $this->dispatch('setFilter', 'evalPeriod', $value);
                            });
                    })(),

                    'label' => __('Evaluation Period: ')
                ],
            ],
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            $this->performance = $row->performancesAsRegular->firstWhere('period_id', $this->period);

            return [
                'class' => $column->getTitle() === 'Evaluatee' ? 'text-md-start border-end sticky' :'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->ofEmploymentStatus(EmploymentStatus::REGULAR)
            ->with([
                'account:account_type,account_id,photo',
                'performancesAsRegular' => [
                    'categoryRatings' => ['rating'],
                ],
            ])
            // ->whereNot('employee_id', Auth::user()->account->employee_id) // not sure abt this
            ->where(function ($query) {
                $query->whereHas('performancesAsRegular', function ($sq) {
                    $sq->whereNotNull('secondary_approver_signed_at');
                });
                
                $query->orWhere(function ($sq) {
                    $sq->whereHas('jobTitle.jobFamily', function ($ssq) {
                        $ssq->where('job_family_id', Auth::user()->account->jobTitle->jobFamily->job_family_id);
                    })->whereHas('performancesAsRegular', function ($ssq) {
                        $ssq->whereNotNull('evaluator_signed_at');
                    });
                });
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('Evaluatee'))
                ->label(fn ($row) => view('components.table.employee')->with([
                        'name'  => $row->full_name,
                        'photo' => $row->account->photo,
                        'id'    => $row->employee_id,
                    ])
                )
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name' ,$direction))
                ->searchable(fn (Builder $query, $searchTerm) => $this->applyFullNameSearch($query, $searchTerm))
                ->setSortingPillDirections('A-Z', 'Z-A'),

            Column::make(__('Completion'))
                ->label(function () {            
                    $url = route("{$this->routePrefix}.performances.regulars.show", [
                        'performance' => $this->performance->regular_performance_id,
                    ]);
                    
                    return "<a href='{$url}' class='btn btn-info btn-md justify-content-center w-auto'>
                                <i data-lucide='eye' class='icon icon-large me-1'></i>
                                <span class='fw-light'>Review Evaluation</span>
                            </a>";
                })
                ->html(),

            Column::make(__('Approval'))
                ->label(function () {
                    $badge = [
                        'color' => StatusBadge::PENDING->getColor(),
                        'slot' => StatusBadge::PENDING->getLabel(),
                    ];
    
                    if ($this->performance) {
                        if ($this->performance->fourth_approver_signed_at) {
                            $badge = [
                                'color' => StatusBadge::APPROVED->getColor(),
                                'slot' => StatusBadge::APPROVED->getLabel(),
                            ];
                        }
                        return view('components.status-badge')->with($badge);
                    }

                    return '--';
                }),

            Column::make(__('Final Rating'))
                ->label(function () {
                    if ($this->performance) {
                        $finalRating = $this->performance->final_rating;

                        return sprintf(
                            '%s - %s',
                            $finalRating['ratingAvg'],
                            $finalRating['performanceScale']
                        );
                    }

                    return '--';
                }),

            Column::make(__('Period'))
                ->label(function () {
                    $period = array_filter(
                        $this->periodOptions,
                        fn ($option) => $option == $this->period
                            ? $this->periodOptions[$option]
                            : null, 
                        ARRAY_FILTER_USE_KEY
                    );

                    return $period ? array_values($period)[0] : '--';
                }),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Evaluation Period'), 'evalPeriod')
                ->options($this->periodOptions)
                ->filter(function (Builder $query, $value) {
                    $query->where(function ($sq) use ($value) {
                        $sq->whereHas('performancesAsRegular', 
                            fn ($ssq) => $ssq->where('period_id', $value)
                        );                        
                    });

                    $this->period = (int) $value;
                })
                ->notResetByClearButton()
                ->setFilterPillTitle('Period')
                ->setFilterDefaultValue(array_key_first($this->periodOptions))
                ->hiddenFromMenus(),

            SelectFilter::make(__('Completion'))
                ->options([
                    '' => __('Select Completion Status'),
                    '0' => __('Incomplete'),
                    '1' => __('Completed'),
                ])
                ->filter(function (Builder $query, $value) {
                    if ($value === '0') {
                        $query->whereDoesntHave(
                            'performancesAsRegular', 
                            fn ($sq) => $sq->where('period_id', $this->period)
                        );
                    } elseif ($value === '1') {
                        $query->whereHas(
                            'performancesAsRegular', 
                            fn ($sq) => $sq->where('period_id', $this->period)
                        );                        
                    }
                })
                ->setFilterDefaultValue(''),

            SelectFilter::make(__('Approval'))
                ->options([
                    '' => __('Select Approval Status'),
                    '0' => __('Pending'),
                    '1' => __('Approved'),
                ])
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('performancesAsRegular', function ($sq) use ($value) {
                        $sq->where('period_id', $this->period);
                        
                        if ($value === '0') {
                            $sq->whereNull('fourth_approver_signed_at');
                        } elseif ($value === '1') {
                            $sq->whereNotNull('fourth_approver_signed_at');
                        }
                    });
                })
                ->setFilterDefaultValue(''),
        ];
    }

    private function getPeriodOptions(): array
    {
        return RegularPerformancePeriod::orderByDesc('start_date')
            ->get()
            ->mapWithKeys(function ($period) {
                return [$period->period_id => $period->interval];
            })->toArray();
    }
}

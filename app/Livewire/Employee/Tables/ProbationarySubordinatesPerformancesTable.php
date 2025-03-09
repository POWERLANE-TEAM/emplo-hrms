<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\EmploymentStatus;
use App\Enums\PerformanceEvaluationPeriod;
use App\Enums\StatusBadge;
use App\Livewire\Tables\Defaults;
use App\Models\Employee;
use App\Models\PerformanceRating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ProbationarySubordinatesPerformancesTable extends DataTableComponent
{
    use Defaults;

    protected $model = Employee::class;

    #[Locked]
    public $routePrefix;

    private $isFinal;

    private $currentPeriod;

    private $performances;

    private $periodOptions;

    private $periodFilter;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id');
        $this->setPageName('probationary-subordinates-performance');
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
                        return SelectFilter::make(__('Type'))
                            ->options($this->periodOptions)
                            ->filter(function (Builder $query, $value) {
                                $this->dispatch('setFilter', 'type', $value);
                            });
                    })(),

                    'label' => __('Stage Type: '),
                ],
            ],
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            $this->performances = $row->performancesAsProbationary
                ->when($this->periodFilter, fn ($query) => $query->where('period_name', $this->periodFilter));
            $this->isFinal = $this->isFinalEvaluated($this->performances);
            $this->currentPeriod = $this->performances->sortByDesc('end_date')->first();

            return [
                'class' => $columnIndex === 0 ? 'text-md-start border-end sticky' : 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->ofEmploymentStatus(EmploymentStatus::PROBATIONARY)
            ->with([
                'account:account_type,account_id,photo',
                'performancesAsProbationary' => [
                    'details' => [
                        'categoryRatings' => ['rating'],
                    ],
                ],
            ])
            ->whereNot('employee_id', Auth::user()->account->employee_id)
            ->whereHas('jobTitle.jobFamily', function ($query) {
                $query->where('job_family_id', Auth::user()->account->jobTitle->jobFamily->job_family_id);
            })
            ->whereHas('performancesAsProbationary');
    }

    private function isFinalEvaluated(Collection $evaluations): ?bool
    {
        $final = $evaluations->filter(function ($item) {
            return $item->period_name === PerformanceEvaluationPeriod::FINAL_MONTH->value;
        })->first();

        return $final?->details?->isNotEmpty();
    }

    private function computeFinalRating($performanceCollection)
    {
        $totals = $performanceCollection->reduce(function ($carry, $item) {
            $categoryRatings = $item->details->first()?->categoryRatings;

            if ($categoryRatings) {
                $totalRatings = $categoryRatings->sum(fn ($subitem) => $subitem->rating->perf_rating);
                $countRatings = $categoryRatings->count();

                $carry['total'] += $totalRatings;
                $carry['count'] += $countRatings;
            }

            return $carry;
        }, ['total' => 0, 'count' => 0]);

        $sum = $totals['total'];
        $countSum = $totals['count'];

        $mean = $countSum > 0 ? $sum / $countSum : 0;
        $format = number_format($mean, 2, '.');
        $rounded = round($mean);
        $avg = (int) $rounded;

        $key = config('cache.keys.performance.ratings');

        $performanceRatings = Cache::rememberForever($key, function () {
            return PerformanceRating::all();
        });

        $scale = $performanceRatings->firstWhere('perf_rating', $avg)?->perf_rating_name;

        return compact('format', 'scale');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Evaluatee'))
                ->label(fn ($row) => view('components.table.employee')->with([
                    'name' => $row->full_name,
                    'photo' => $row->account->photo,
                    'id' => $row->employee_id,
                ]))
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name', $direction))
                ->searchable(fn (Builder $query, $searchTerm) => $this->applyFullNameSearch($query, $searchTerm)),

            Column::make(__('Stage Type'))
                ->label(fn () => PerformanceEvaluationPeriod::from($this->currentPeriod->period_name)->getLabel()),

            Column::make(__('Completion'))
                ->label(function ($row) {
                    $evaluations = $row->performancesAsProbationary;

                    if ($this->isFinal) {
                        session()->put('final_rating', $this->computeFinalRating($evaluations));

                        $url = route("{$this->routePrefix}.performances.probationaries.show", [
                            'employee' => $row->employee_id,
                            'year_period' => $evaluations->max('end_date')->year,
                        ]);

                        return "<a href='{$url}' class='btn btn-info btn-sm justify-content-center w-auto'>
                                    <i data-lucide='eye' class='icon icon-large me-1'></i>
                                    <span class='fw-light'>Review Evaluation</span>
                                </a>";
                    }

                    $url = route("{$this->routePrefix}.performances.probationaries.create", [
                        'employee' => $row->employee_id,
                    ]);

                    if ($evaluations->contains(fn ($evaluation) => $evaluation->details->contains('period_id', $this->currentPeriod->period_id))
                    ) {
                        return "<a href='{$url}' class='btn btn-info btn-sm justify-content-center w-auto'>
                                    <i data-lucide='eye' class='icon icon-large me-1'></i>
                                    <span class='fw-light'>Review Evaluation</span>
                                </a>";
                    }

                    if ($evaluations->max('end_date')->ne($this->currentPeriod->end_date)) {
                        return view('components.status-badge')->with([
                            'color' => StatusBadge::INVALID->getColor(),
                            'slot' => __('Not Applicable'),
                        ]);
                    }

                    return "<a href='{$url}' class='btn btn-primary btn-sm justify-content-center w-auto'>
                                <i data-lucide='pen' class='icon icon-medium me-1'></i>
                                <span class='fw-light'>Start Evaluation</span>
                            </a>";
                })
                ->html(),

            Column::make(__('Approval'))
                ->label(function ($row) {
                    $badge = [
                        'color' => StatusBadge::PENDING->getColor(),
                        'slot' => StatusBadge::PENDING->getLabel(),
                    ];

                    if ($this->isFinal) {
                        $isFullyApproved = $this->performances->first(function ($performance) {
                            return $performance->period_name === PerformanceEvaluationPeriod::FINAL_MONTH->value;
                        })->details->first(fn ($detail) => $detail->fourth_approver_signed_at);

                        if ($isFullyApproved) {
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
                ->label(function ($row) {
                    if ($this->isFinal) {
                        $finalRating = $this->computeFinalRating($this->performances);

                        return sprintf(
                            '<strong>%s - %s</strong>',
                            $finalRating['format'],
                            $finalRating['scale']
                        );
                    }

                    return '--';
                })
                ->html(),

            Column::make(__('Period'))->label(fn () => $this->currentPeriod->interval),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Type'))
                ->options($this->periodOptions)
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('performancesAsProbationary', function ($subQuery) use ($value) {
                        $subQuery->where('period_name', $value);
                    });

                    $this->periodFilter = $value;
                })
                ->setFilterPillTitle('Type')
                ->hiddenFromMenus()
                ->hiddenFromFilterCount(),
        ];
    }

    private function getPeriodOptions(): array
    {
        $filtered = array_filter(
            PerformanceEvaluationPeriod::options(),
            fn ($option) => $option != PerformanceEvaluationPeriod::ANNUAL->value,
            ARRAY_FILTER_USE_KEY
        );

        return ['' => __('Select a type')] + $filtered;
    }
}

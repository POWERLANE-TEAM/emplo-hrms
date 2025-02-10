<?php

namespace App\Livewire\Employee\Tables;

use Livewire\Attributes\Locked;
use App\Models\RegularPerformance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class MyPerformancesAsRegularTable extends DataTableComponent
{
    protected $model = RegularPerformance::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('regular_performance_id')
            ->setTableRowUrl(function ($row) {
                return route("{$this->routePrefix}.performances.regular.show", [
                    'performance' => $row->regular_performance_id,
                ]) . '/#overview';
            })
            ->setTableRowUrlTarget(fn() => '__blank');
        $this->setPageName('my-regular-performance');
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
                    'heading' => __('Current Period: ') . now()->format('F d, Y'),
                ],
            ],
        ]);
    }

    public function builder(): Builder
    {
        return RegularPerformance::query()
            ->with([
                'employeeEvaluatee',
                'period',
                'categoryRatings.rating',
            ])
            ->where('evaluatee', Auth::user()->account->employee_id)
            ->whereHas('employeeEvaluatee', function ($query) {
                $query->where('employee_id', Auth::user()->account->employee_id);
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('Status'))
                ->label(function ($row) {
                    if ($row) {
                        return __('Completed');
                    } else {
                        return __('Incomplete');
                    }
                }),

            Column::make(__('Results'))
                ->label(function ($row) {
                    if ($row) {
                        if ($row->fourth_approver_signed_at) {
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
                    if ($row) {
                        $finalRating = $row->final_rating;
                        return $finalRating['ratingAvg'];
                    } else {
                        return '-';
                    }
                }),

            Column::make(__('Performance Scale'))
                ->label(function ($row) {
                    if ($row) {
                        $finalRating = $row->final_rating;
                        return $finalRating['performanceScale'];
                    } else {
                        return '-';
                    }
                }),

            Column::make(__('Period'))
                ->label(function ($row) {
                    if (is_null($row)) {
                        return ' - ';
                    } else {
                        return $row->period->interval;
                    }
                }),
        ];
    }
}

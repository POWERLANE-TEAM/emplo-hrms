<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Overtime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class OvertimeRequestCutoffsTable extends DataTableComponent
{
    private $cutOff;

    private $routePrefix;

    public function configure(): void
    {
        $this->routePrefix = Auth::user()->account_type;

        $this->setPrimaryKey('payroll_id')
            ->setTableRowUrl(function ($row) {
                $cutOffFilter = [
                    'start' => $row->payrollApproval->cut_off_start,
                    'end' => $row->payrollApproval->cut_off_end,
                ];

                $filterParams = $this->buildDateRangeFilterParams($cutOffFilter);
                
                return route("{$this->routePrefix}.overtimes.requests").'?'.
                    http_build_query($filterParams);
            })
            ->setTableRowUrlTarget(fn () => '__blank');
        
        $this->setPageName('overtime-requests');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        // $this->setDefaultSort('cut_off_start', 'desc');
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

        // $this->setConfigurableAreas([
        //     'toolbar-left-start' => [
        //         'components.headings.main-heading',
        //         [
        //             'overrideClass' => true,
        //             'overrideContainerClass' => true,
        //             'attributes' => new ComponentAttributeBag([
        //                 'class' => 'fs-5 py-1 text-secondary-emphasis fw-medium text-underline',
        //             ]),
        //             'heading' => __('OT Summaries Per Cut-Off Period'),
        //         ],
        //     ],
        // ]);
    }

    private function buildDateRangeFilterParams(mixed $date)
    {
        return [
            'table-filters' => [
                'cut-_off_period' => [
                    'minDate' => $date['start'],
                    'maxDate' => $date['end'],
                ],
            ],
        ];
    }

    public function builder(): Builder
    {
        $statement = "
            count(overtime_id) as overtime_id, 
            max(filed_at) as filed_at,
            max(date) as date,
            payroll_approval_id, 
            sum(abs(extract(epoch from (start_time - end_time)))) / 3600 as total_ot_hours
        ";

        return Overtime::query()
            ->with([
                'payrollApproval.payroll',
                'employee.jobTitle.jobFamily',
            ])
            ->whereHas('employee.jobTitle.jobFamily', function ($query) {
                $query->where('job_family_id', Auth::user()->account->jobTitle->jobFamily->job_family_id);
            })
            ->selectRaw($statement)
            ->groupBy('payroll_approval_id');
    }
    
    public function columns(): array
    {
        return [
            Column::make(__('Cut-Off Period'))
                ->label(fn ($row) => $row->payrollApproval->payroll->cut_off)
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('payrollApproval.payroll', function ($subquery) use ($direction) {
                        $subquery->orderBy('cut_off_start', $direction);
                        $subquery->orderBy('cut_off_end', $direction);
                    });
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Cut-Off')),

            Column::make(__('Payout Date'))
                ->label(fn ($row) => $row->payrollApproval->payroll->payout)
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('payrollApproval.payroll', function ($subquery) use ($direction) {
                        $subquery->orderBy('payout', $direction);
                    });
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Payout')),

            Column::make(__('Last Request Filing'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Last filing')),

            Column::make(__('Total OT Hours'))
                ->label(function ($row) {
                    $seconds = $row->total_ot_hours * 3600;
                    $hours = floor($seconds / 3600);
                    $minutes = floor(($seconds % 3600) / 60);
                
                    return __("{$hours} hours and {$minutes} minutes");
                })
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('total_ot_hours', $direction); 
                })
                ->setSortingPillDirections('High', 'Low')
                ->setSortingPillTitle(__('Hours rendered')),
        ];
    }

    // public function filters(): array
    // {
    //     return [
    //         DateFilter::make(__('Last Filing Date'))
    //             ->config([
    //                 'max' => now()->format('Y-m-d'),
    //                 'pillFormat' => 'd M Y',
    //             ])
    //             ->filter(function (Builder $query, $value) {
    //                 return $query->whereDate('filed_at', $value);
    //             }),
    //     ];
    // }
}

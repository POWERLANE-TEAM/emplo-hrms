<?php

namespace App\Livewire\Employee\Tables\Basic;

use App\Models\Overtime;
use App\Http\Helpers\Payroll;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

class OvertimeSummariesTable extends DataTableComponent
{
    private $cutOff;

    public function configure(): void
    {
        $routePrefix = Auth::user()->account_type;

        $this->setPrimaryKey('overtime_id')
            ->setTableRowUrl(fn () => route($routePrefix.'.overtimes.archive'))
            ->setTableRowUrlTarget(fn () => 'navigate');
        
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
            $this->cutOff = Payroll::getCutOffPeriod(isReadableFormat: true),
            
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-5 py-1 text-secondary-emphasis fw-medium text-underline',
                    ]),
                    'heading' => __('OT Summaries Per Cut-Off Period'),
                ],
            ],
        ]);

    }

    public function builder(): Builder
    {
        $cutOffs = $this->getAllCutOffs(now()->toDateTimeString(), 12);
        
        $periods = array_map(function ($cutOff) {
            $start = $cutOff['start']->toDateTimeString();
            $end = $cutOff['end']->toDateTimeString();
            return "when filed_at between '{$start}' and '{$end}' then '{$start} - {$end}'";
        }, $cutOffs);
    
        $period = implode(' ', $periods);
        $statement = "
            max(filed_at) as filed_at, 
            (case {$period} end) as cut_off_period,
            sum(abs(extract(epoch from (start_time - end_time)))) / 3600 as total_hours_rendered
        ";

        $interval = array_map(function ($cutOff) {
            $start = $cutOff['start']->toDateTimeString();
            $end = $cutOff['end']->toDateTimeString();
            return "filed_at between '{$start}' AND '{$end}'";
        }, $cutOffs);
    
        $condition = implode(' or ', $interval);
        
        return Overtime::query()
            ->where('employee_id', Auth::user()->account->employee_id)
            ->selectRaw($statement)
            ->whereRaw("($condition)")
            ->groupByRaw('cut_off_period');
    }
    
    private function getAllCutOffs($date = null, $numPeriods = 12): array
    {
        $date = Carbon::parse($date) ?? now();
        $periods = [];
        
        for ($i = 0; $i < $numPeriods; $i++) {
            if ($date->day >= 11 && $date->day <= 25) {
                $start = $date->copy()->day(11)->startOfDay();
                $end = $date->copy()->day(25)->endOfDay();
                $periods[] = compact('start', 'end');
                
                $start = $date->copy()->day(26)->startOfDay();
                $end = $date->copy()->addMonth()->day(10)->endOfDay();
                $periods[] = compact('start', 'end');
            } elseif ($date->day >= 26) {
                $start = $date->copy()->day(26)->startOfDay();
                $end = $date->copy()->addMonth()->day(10)->endOfDay();
                $periods[] = compact('start', 'end');
            } else {
                $start = $date->copy()->subMonth()->day(26)->startOfDay();
                $end = $date->copy()->subMonth()->day(10)->endOfDay();
                $periods[] = compact('start', 'end');
            }
    
            $date->subMonth();
        }
    
        return $periods;
    }

    public function columns(): array
    {
        return [
            Column::make(__('Cut-Off Period'))
                ->label(function ($row) {
                    $cutOff = Payroll::getCutOffPeriod($row->filed_at, isReadableFormat: true);
                    return $cutOff['start']. ' - ' .$cutOff['end'];
                })
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Cut-Off')),

            Column::make(__('Payout Date'))
                ->label(fn ($row) => Payroll::getPayoutDate($row->filed_at, isReadableFormat: true))
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
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

            Column::make(__('Hours Rendered'))
                ->label(function ($row) {
                    $seconds = $row->total_hours_rendered * 3600;
                    $hours = floor($seconds / 3600);
                    $minutes = floor(($seconds % 3600) / 60);
                
                    return __("{$hours} hours and {$minutes} minutes");
                })
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('total_hours_rendered', $direction); 
                })
                ->setSortingPillDirections('High', 'Low')
                ->setSortingPillTitle(__('Hours rendered')),
        ];
    }

    public function filters(): array
    {
        return [
            DateFilter::make(__('Last Filing Date'))
                ->config([
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                ])
                ->filter(function (Builder $query, $value) {
                    return $query->whereDate('filed_at', $value);
                }),
        ];
    }
}

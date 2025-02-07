<?php

namespace App\Livewire\Employee\Tables\Basic;

use App\Enums\Payroll;
use App\Models\Overtime;
use App\Enums\StatusBadge;
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
            ->setTableRowUrl(function ($row) use ($routePrefix) {
                $filterParams = $this->appendPayrollId($row->payrollApproval->payroll->payroll_id);

                return route("{$routePrefix}.overtimes.summaries").'?'.http_build_query($filterParams);
            })
            ->setTableRowUrlTarget(fn () => '__blank');
        
        $this->setPageName('overtime-requests');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->enableColumnSelectEvent();
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

    private function appendPayrollId(int $payrollId)
    {
        return [
            'table-filters' => [
                'payroll' => $payrollId,
            ],
        ];
    }

    public function builder(): Builder
    {
        $statement = "
            count(overtime_id) as overtime_id, 
            max(filed_at) as filed_at,
            payroll_approval_id
        ";

        return Overtime::query()
            ->with('payrollApproval',)
            ->where('employee_id', Auth::user()->account->employee_id)
            ->selectRaw($statement)
            ->groupBy('payroll_approval_id');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Cut-Off Period'))
                ->label(fn ($row) => $row->payrollApproval->payroll->cut_off)
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Cut-Off')),

            Column::make(__('Approval Status'))
                ->label(function ($row) {
                    $badge = [];
                    
                    if ($row->payrollApproval->third_approver_signed_at) {
                        $badge = [
                            'color' => StatusBadge::APPROVED->getColor(),
                            'slot' => StatusBadge::APPROVED->getLabel(),
                        ];
                    } else {
                        $badge = [
                            'color' => StatusBadge::PENDING->getColor(),
                            'slot' => StatusBadge::PENDING->getLabel(),
                        ];
                    }

                    return view('components.status-badge')->with($badge);
                }),

            Column::make(__('Payout Date'))
                ->label(fn ($row) => $row->payrollApproval->payroll->payout)
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Payout')),

            Column::make(__('Last Request Filing'))
                ->label(fn ($row) => $row->filed_at->format('F d, Y g:i A'))
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Last filing')),

            // Column::make(__('Hours Rendered'))
            //     ->label(function ($row) {
            //         $seconds = $row->total_hours_rendered * 3600;
            //         $hours = floor($seconds / 3600);
            //         $minutes = floor(($seconds % 3600) / 60);
                
            //         return __("{$hours} hours and {$minutes} minutes");
            //     })
            //     ->sortable(function (Builder $query, $direction) {
            //         return $query->orderBy('total_hours_rendered', $direction); 
            //     })
            //     ->setSortingPillDirections('High', 'Low')
            //     ->setSortingPillTitle(__('Hours rendered')),
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
<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\StatusBadge;
use App\Enums\UserPermission;
use App\Models\Overtime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OvertimeRequestSummariesTable extends DataTableComponent
{
    private $cutOff;

    private $routePrefix;

    public function configure(): void
    {
        $this->routePrefix = Auth::user()->account_type;

        $this->setPrimaryKey('overtime_summary_id')
            ->setTableRowUrl(function ($row) {
                $filterParams = $this->appendPayrollId($row->payrollApproval->payroll->payroll_id);

                return route("{$this->routePrefix}.overtimes.requests.employee.summaries", [
                    'employee' => $row->employee_id,
                ]).'?'.http_build_query($filterParams);
            })
            ->setTableRowUrlTarget(fn () => '__blank');

        $this->setPageName('overtime-requests');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        // $this->setDefaultSort('requestor_id', 'desc');
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
                'class' => 'text-md-center fw-medium',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => $columnIndex === 0 ? 'text-md-start' : 'flex text-md-center',
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

    private function getEmployeeId(int $employeeId)
    {
        return [
            'table-filters' => [
                'employee' => $employeeId,
            ],
        ];
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
        $statement = '
            count(overtime_id) as overtime_id, 
            max(filed_at) as filed_at,
            payroll_approval_id, 
            employee_id
        ';

        return Overtime::query()
            ->with([
                'employee',
                'employee.account',
                'payrollApproval.payroll',
                'employee.jobTitle.jobFamily',
            ])
            ->whereHas('employee', function ($query) {
                $user = Auth::user();
                if (! $user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME_REQUEST)) {
                    $query->whereNot('employee_id', Auth::user()->account->employee_id)
                        ->whereHas('jobTitle.jobFamily', function ($query) {
                            $query->where('job_family_id', Auth::user()->account->jobTitle->jobFamily->job_family_id);
                        });
                } else {
                    $query->whereNot('employee_id', Auth::user()->account->employee_id);
                }
            })
            ->selectRaw($statement)
            ->groupBy('payroll_approval_id', 'employee_id');
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
                                <img src="'.e($photo).'" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>'.e($name).'</div>
                                    <div class="text-muted fs-6">Employee ID: '.e($id).'</div>
                                </div>
                            </div>';
                })
                ->html(),

            Column::make(__('Cut-Off Period'))
                ->label(fn ($row) => $row->payrollApproval->payroll->cut_off),

            Column::make(__('Approval Status'))
                ->label(function ($row) {
                    $badge = [
                        'color' => StatusBadge::PENDING->getColor(),
                        'slot' => StatusBadge::PENDING->getLabel(),
                    ];

                    if ($row->payrollApproval->third_approver_signed_at) {
                        $badge = [
                            'color' => StatusBadge::APPROVED->getColor(),
                            'slot' => StatusBadge::APPROVED->getLabel(),
                        ];
                    }

                    return view('components.status-badge')->with($badge);
                }),

            Column::make(__('Last Request Filing'))
                ->label(fn ($row) => $row->filed_at->format('F d, Y g:i A'))
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc')
                ->setSortingPillTitle(__('Last filing')),

            // Column::make(__('Total OT Hours'))
            //     ->label(function ($row) {
            //         $seconds = $row->employee->overtimes->sum(function ($ot) {
            //             return $ot->authorizer_signed_at
            //                 ? $ot->start_time->diffInSeconds($ot->end_time)
            //                 : null;
            //         });

            //         $hours = floor($seconds / 3600);
            //         $minutes = floor(($seconds % 3600) / 60);

            //         return __("{$hours} hours and {$minutes} minutes");
            //     })
            //     ->setSortingPillDirections('High', 'Low')
            //     ->setSortingPillTitle(__('Hours rendered')),
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

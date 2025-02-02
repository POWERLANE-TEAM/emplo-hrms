<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\UserPermission;
use App\Models\Overtime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

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
                    'employee' => $row->employee_id
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
                'class' => 'text-md-start fw-medium',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'flex text-md-start',
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
        $statement = "
            count(overtime_id) as overtime_id, 
            max(filed_at) as filed_at,
            max(date) as date,
            payroll_approval_id, 
            employee_id,
            sum(abs(extract(epoch from (start_time - end_time)))) / 3600 as total_ot_hours
        ";

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
            // Column::make(__('Employee'))
            //     ->label(fn ($row) => dd($row->overtime->employee)),

            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->employee->full_name);
                    $photo = $row->employee->account->photo;
                    $id = $row->employee->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html(),

            Column::make(__('Cut-Off Period'))
                ->label(fn ($row) => $row->payrollApproval->payroll->cut_off),

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
                    return $query->orderBy('total_hours_rendered', $direction); 
                })
                ->setSortingPillDirections('High', 'Low')
                ->setSortingPillTitle(__('Hours rendered')),

            Column::make(__('Status'))
                ->label(function ($row) {
                    if ($row->payrollApproval->third_approver_signed_at) {
                        return __('Approved');
                    } else {
                        return __('Pending');
                    }
                }),
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

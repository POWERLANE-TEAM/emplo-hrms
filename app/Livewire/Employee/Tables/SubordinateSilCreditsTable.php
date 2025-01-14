<?php

namespace App\Livewire\Employee\Tables;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ServiceIncentiveLeaveCredit;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class SubordinateSilCreditsTable extends DataTableComponent
{
    protected $model = ServiceIncentiveLeaveCredit::class;

    public function configure(): void
    {
        $this->setPrimaryKey('sil_credit_id');
        $this->setPageName('sub_sil_credits');
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
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start' : 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return ServiceIncentiveLeaveCredit::query()
            ->with([
                'employee' => [
                    'account',
                    'status',
                ],
            ])
            ->whereHas('employee', function ($query) {
                $query->whereHas('jobTitle.jobFamily', function ($subQuery) {
                    $subQuery->where('job_family_id', Auth::user()->account->jobTitle->jobFamily->job_family_id);
                });
                $query->whereHas('status', function ($subQuery) {
                    $subQuery->whereNotIn('emp_status_name', [
                        EmploymentStatus::RESIGNED->label(),
                        EmploymentStatus::RETIRED->label(),
                        EmploymentStatus::TERMINATED->label(),
                    ]);
                });
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->employee->full_name);
                    $photo = $row->employee->account->photo;
                    $id = $row->employee->employee_id;
                    $status = $row->employee->status->emp_status_name;
            
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
                    return $query->whereHas('employee', function ($subQuery) use ($searchTerm) {
                        $subQuery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%")
                            ->orWhereHas('account', fn ($query) => $query->orWhereLike('email', "%{$searchTerm}%"));
                    });
                }),

            Column::make(__('Vacation Leave Credits'))
                ->label(fn ($row) => $row->vacation_leave_credits),

            Column::make(__('Sick Leave Credits'))
                ->label(fn ($row) => $row->sick_leave_credits),

            // Column::make(__('Years Of Service'))
            Column::make(__('Date Hired'))
                ->label(fn ($row) => Carbon::make($row->employee->jobDetail->hired_at)->format('F d, Y')),
        ];
    }
}

<?php

namespace App\Livewire\Employee\Tables\Basic;

use App\Models\Overtime;
use App\Enums\StatusBadge;
use Livewire\Attributes\On;
use App\Enums\OvertimeRequestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ArchiveOvertimesTable extends DataTableComponent
{
    protected $model = Overtime::class;

    public function configure(): void
    {
        $this->setPrimaryKey('overtime_id');
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
        $this->setRememberColumnSelectionDisabled();

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            $eventPayload = $this->createEventPayload($row);

            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
                'role' => 'button',
                'wire:click' => "\$dispatchTo(
                    'employee.overtimes.overtime-summary-approval', 
                    'showOvertimeSummaryApproval', 
                    { eventPayload: ".json_encode($eventPayload)."})",
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
    }

    private function createEventPayload($row)
    {
        return [
            'payroll'               => $row->payrollApproval->payroll->cut_off,
            'work_performed'        => $row->work_performed,
            'start_time'            => $row->start_time->format('F d, Y g:i A'),
            'end_time'              => $row->end_time->format('F d, Y g:i A'),
            'hours_requested'       => $row->hours_requested,
            'authorizer_signed_at'  => $row->authorizer_signed_at?->format('F d, Y g:i A'),
            'authorizer'            => $row?->authorizedBy?->full_name,
            'denied_at'             => $row->denied_at?->format('F d, Y g:i A'),
            'denier'                => $row?->deniedBy?->full_name,
            'feedback'              => $row->feedback,
            'filed_at'              => $row->filed_at->format('F d, Y g:i A'),
            'modified_at'           => $row->modified_at->format('F d, Y g:i A'),
        ];
    }

    public function builder(): Builder
    {
        return Overtime::query()
            ->with([
                'payrollApproval.payroll',
                'authorizedBy',
                'deniedBy',
            ])
            ->select('*')
            ->where('employee_id', Auth::user()->account->employee_id)
            ->where(function ($query) {
                $query->archived()
                    ->orWhere(fn ($subQuery) => $subQuery->authorized())
                    ->orWhere(fn ($subQuery) => $subQuery->denied());
            });
    }

    private function getPayrollOptions()
    {
        return $this->builder()->get()
            ->mapWithKeys(function ($item) {
                $payroll = $item->payrollApproval->payroll;
                return [$payroll->payroll_id => $payroll->cut_off];
            })
            ->toArray();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Work To Perform'))
                ->label(fn ($row) => $row->work_performed)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('work_performed', $direction))
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('work_performed', "%{$searchTerm}%"))
                ->deselected(),

            Column::make(__('Start Time'))
                ->format(fn ($row) => $row->format('F d, Y g:i A'))
                ->sortable()
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('End Time'))
                ->format(fn ($row) => $row->format('F d, Y g:i A'))
                ->sortable()
                ->setSortingPillDirections('Asc', 'Desc'),
            
            Column::make(__('Hours Requested'))
                ->label(fn ($row) => $row->hoursRequested)
                ->setSortingPillDirections('Asc', 'Desc'),
            
            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at->format('F d, Y g:i A'))
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Authorization'))
                ->label(function ($row) {
                    $badge = [];

                    if ($row->authorizer_signed_at) {
                        return $row->authorizedBy->full_name;
                    } elseif ($row->denied_at) {
                        $badge = [
                            'color' => StatusBadge::DENIED->getColor(),
                            'slot' => StatusBadge::DENIED->getLabel(),
                        ];
                    } else {
                        $badge = [
                            'color' => StatusBadge::PENDING->getColor(),
                            'slot' => StatusBadge::PENDING->getLabel(),
                        ];
                    }

                    return view('components.status-badge')->with($badge);
                }),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Payroll'), 'payroll')
                ->options($this->getPayrollOptions())
                ->setFilterPillTitle('Payroll')
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('payrollApproval.payroll', function ($subquery) use ($value) {
                        $subquery->where('payroll_id', $value);
                    });
                }),

            DateFilter::make(__('Filing Date'))
                ->config([
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                ])
                ->filter(function (Builder $query, $value) {
                    return $query->whereDate('filed_at', $value);
                }),

            SelectFilter::make(__('Request Status'))
                ->options(
                    array_reduce(
                        OvertimeRequestStatus::cases(),
                        fn ($options, $case) => $options + [$case->value => $case->getLabel()],
                        []
                    )
                )
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('approvals', function ($subquery) use ($value) {
                        if ($value === OvertimeRequestStatus::APPROVED->value) {
                            $subquery->whereNotNull('secondary_approver_signed_at');
                        } elseif ($value === OvertimeRequestStatus::PENDING->value) {
                            $subquery->whereNull('secondary_approver_signed_at');
                        } elseif ($value === OvertimeRequestStatus::DENIED->value) {
                            $subquery->whereNotNull('denied_at');
                        }
                    });
                })
        ];
    }
}

<?php

namespace App\Livewire\Employee\Tables\Basic;

use App\Models\Overtime;
use App\Enums\StatusBadge;
use App\Enums\OvertimeRequestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RecentOvertimesTable extends DataTableComponent
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
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
                'role' => 'button',
                'wire:click' => "\$dispatchTo(
                    'employee.overtimes.basic.edit-overtime-request', 
                    'showOvertimeRequest', 
                    { overtimeId: $row->overtime_id })",
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

    public function builder(): Builder
    {
        return Overtime::query()
            ->with([
                'authorizedBy',
            ])
            ->select('*')
            ->where('employee_id', Auth::user()->account->employee_id)
            ->where(fn ($query) => $query->recent());
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
                    if ($value === OvertimeRequestStatus::APPROVED->value) {
                        $query->whereNotNull('authorizer_signed_at');
                    } elseif ($value === OvertimeRequestStatus::PENDING->value) {
                        $query->whereNull('authorizer_signed_at')
                            ->whereNull('denied_at');
                    } elseif ($value === OvertimeRequestStatus::DENIED->value) {
                        $query->whereNotNull('denied_at');
                    }
                })
        ];
    }
}

<?php

namespace App\Livewire\Employee\Tables\Basic;

use App\Models\Overtime;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

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

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            $attributes = [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
            ];

            $pendingStatus = is_null($row->processes->first()->secondary_approver_signed_at);

            if ($pendingStatus) {
                $attributes = [
                    'role' => 'button',
                    'wire:click' => "\$dispatch('showOvertimeRequest', $row->overtime_id)",
                ];
            }

            return $attributes;
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
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
            ->with(['employee', 'processes'])
            ->select('*')
            ->where('employee_id', Auth::user()->account->employee_id)
            ->where('filed_at', '>=', Carbon::now()->subWeek());
    }

    #[On('overtimeRequestCreated')]
    public function refreshDataTable()
    {
        $this->render();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Work Performed'))
                ->searchable(),

            Column::make(__('Start Time'))
                ->sortable()
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('End Time'))
                ->sortable()
                ->setSortingPillDirections('Asc', 'Desc'),
            
            Column::make(__('Hours Requested'))
                ->label(fn ($row) => $row->getHoursRequested())
                ->setSortingPillDirections('Asc', 'Desc'),

            Column::make(__('Status'))
                ->label(function ($row) {
                    return $row->processes->first()->secondary_approver_signed_at
                        ? __('Approved')
                        : __('Pending');
                }),
            
            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy('filed_at', $direction);
                })
                ->setSortingPillDirections('Asc', 'Desc'),
        ];
    }
}
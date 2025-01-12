<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Employee;
use App\Models\Training;
use App\Enums\TrainingStatus;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class IndividualEmployeeTrainingsTable extends DataTableComponent
{
    protected $model = Training::class;

    public Employee $employee;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id');
        $this->setPageName('individual-training');
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
                'class' => 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return Training::query()
            ->with([
                'employeeTrainee',
                'trainer',
            ])
            ->whereHas('employeeTrainee', function ($query) {
                $query->where('employee_id', $this->employee->employee_id);
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('Training'))
                ->label(fn ($row) => $row->training_title),

            Column::make(__('Trainer'))
                ->label(fn ($row) => $row->trainer?->full_name),

            Column::make(__('Training Provider'))
                ->label(fn ($row) => $row->trainer?->provider?->training_provider_name ?? __('N/A')),

            Column::make(__('Status'))
                ->label(fn ($row) => TrainingStatus::from($row->completion_status)->getLabel()),

            Column::make(__('Duration'))
                ->label(function ($row) {
                    $start = Carbon::parse($row->start_date);
                    $end = Carbon::parse($row->end_date);

                    return "{$start->diffInHours($end)} hours";
                }),

            Column::make(__('Expiry'))
                ->label(fn ($row) => Carbon::make($row->expiry_date)->format('F d, Y g:i A')),
        ];
    }
}

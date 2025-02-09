<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Training;
use App\Enums\TrainingStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class MyTrainingsTable extends DataTableComponent
{
    protected $model = Training::class;

    public function configure(): void
    {
        $this->setPrimaryKey('training_id');
        $this->setPageName('my-trainings');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('created_at', 'desc');
        $this->setSearchDebounce(1000);
        $this->setTrimSearchStringEnabled();
        $this->setEmptyMessage(__('No results found.'));
        $this->setPerPageAccepted([5, 10, 25, 50, -1]);
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
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start' : 'text-md-center',
            ];
        });

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-5 py-1 text-secondary-emphasis fw-semibold text-underline',
                    ]),
                    'heading' => __('Training Records'),
                ],
            ],
        ]);
    }

    public function builder(): Builder
    {
        return Training::query()
            ->with([
                'employeeTrainee',
                'trainer',
            ])
            ->where('trainee', Auth::user()->account->employee_id);
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

            Column::make(__('Start Date'))
                ->label(fn ($row) => Carbon::make($row->start_date)->format('F d, Y g:i A'))
                ->deselected(),

            Column::make(__('End Date'))
                ->label(fn ($row) => Carbon::make($row->end_date)->format('F d, Y g:i A'))
                ->deselected(),

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

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Completion'))
                ->options(TrainingStatus::options())
                ->filter(function (Builder $query, $value) {
                    $query->where('completion_status', $value);
                }),
        ];
    }
}

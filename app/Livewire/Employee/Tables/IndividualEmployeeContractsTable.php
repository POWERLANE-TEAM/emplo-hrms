<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\FilePath;
use App\Models\Contract;
use App\Models\Employee;
use App\Enums\ContractType;
use App\Http\Helpers\FileSize;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class IndividualEmployeeContractsTable extends DataTableComponent
{
    protected $model = Contract::class;

    public Employee $employee;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('contract_id');
        $this->setPageName('contract');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('created_at', 'desc');
        $this->setSearchDebounce(1000);
        $this->setTrimSearchStringEnabled();
        $this->setEmptyMessage(__('No results found.'));
        $this->setPerPageAccepted([5, 25, 50, 100, -1]);
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
                'class' => 'text-md-center',
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
                    'heading' => __("Contracts & Amendments"),
                ],
            ],
        ]);
    }

    // #[On('contractUploaded')]
    // #[On('addendumUploaded')]
    // public function refreshDataTableComponent()
    // {
    //     $this->dispatch('refreshDatatable');
    // }

    public function builder(): Builder
    {
        return Contract::query()
            ->with([
                'uploader',
            ])
            ->whereHas('employee', function ($query) {
                $query->where('employee_id', $this->employee->employee_id);
            });
    }

    public function columns(): array
    {
        return [
            LinkColumn::make(__('File Name'))
                ->title(fn ($row) => $row->attachment_name ?? '-')
                ->location(fn ($row) => route("{$this->routePrefix}.files.contracts.attachments", [
                    'attachment' => $row->hashed_attachment,
                ]))
                ->attributes(fn ($row) => [
                    'target' => '__blank',
                    'class' => 'text-primary',
                    'alt' => "{$row->attachment_name}",
                ])
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('attachment_name', $direction))
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('attachment_name', "%{$searchTerm}%")),

            Column::make(__('Contract Type'))
                ->label(fn ($row) => ContractType::from($row->type)->getLabel()),

            Column::make(__('File Size'))
                ->label(function ($row) {
                    Storage::disk('local')->makeDirectory(FilePath::CONTRACTS->value);
                    $path = sprintf('%s/%s', FilePath::CONTRACTS->value, $row->hashed_attachment);
                    $sizeInBytes = Storage::disk('local')->size($path);

                    return FileSize::formatSize($sizeInBytes);
                }),

            Column::make(__('Last Modified'))
                ->label(function ($row) {
                    Storage::disk('local')->makeDirectory(FilePath::CONTRACTS->value);
                    $path = sprintf('%s/%s', FilePath::CONTRACTS->value, $row->hashed_attachment);
                    $lastModified = Storage::disk('local')->lastModified($path);
                    $convertFormat = Carbon::createFromTimestamp($lastModified, config('app.timezone'))->format('F d, Y g:i A');

                    return $convertFormat;
                }),

            Column::make(__('Uploaded By'))
                ->label(fn ($row) => $row->uploader->full_name)
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('uploader', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('employees.first_name', "%{$searchTerm}%")
                            ->orWhereLike('employees.middle_name', "%{$searchTerm}%")
                            ->orWhereLike('employees.last_name', "%{$searchTerm}%");
                    });
                }),
            
            Column::make(__('Uploaded At'))
                ->label(fn ($row) => Carbon::make($row->created_at)->format('F d, Y g:i A'))
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('created_at', $direction))
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('created_at', "%{$searchTerm}%"))
                ->setSortingPillDirections('Oldest', 'Latest'),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Contract Type'))
                ->options(ContractType::options())
                ->filter(function (Builder $query, string $value) {
                    $query->where('type', $value);
                }),
        ];
    }
}

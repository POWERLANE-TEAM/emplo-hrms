<?php

namespace App\Livewire\Tables;

use App\Models\JobFamily;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class JobFamilyTable extends DataTableComponent
{
    protected $model = JobFamily::class;

    public function configure(): void
    {
        $this->setPrimaryKey('job_family_id');
        $this->setPageName('job_families');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('created_at', 'desc');
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
                'class' => $column->getTitle() === 'Supervisor' ||
                    $column->getTitle() === 'Office Head'
                        ? 'text-md-start'
                        : 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return JobFamily::query()
            ->with([
                'officeSupervisor' => [
                    'account',
                ],
                'head' => [
                    'account',
                ],
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Job Family'))
                ->label(fn ($row) => $row->job_family_name)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('job_family_name', $direction))
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('job_family_name', "%{$searchTerm}%")),

            Column::make(__('Supervisor'))
                ->label(function ($row) {
                    $name = Str::headline($row->officeSupervisor->full_name);
                    $photo = $row->officeSupervisor->account->photo;
                    $id = $row->officeSupervisor->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name' ,$direction))
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('officeSupervisor', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%")
                            ->orWhereHas('account', fn ($query) => $query->orWhereLike('email', "%{$searchTerm}%"));
                    });
                }),
            
            Column::make(__('Office Head'))
                ->label(function ($row) {
                    $name = Str::headline($row->head->full_name);
                    $photo = $row->head->account->photo;
                    $id = $row->head->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name' ,$direction))
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('head', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%")
                            ->orWhereHas('account', fn ($query) => $query->orWhereLike('email', "%{$searchTerm}%"));
                    });
                }),
        ];
    }
}

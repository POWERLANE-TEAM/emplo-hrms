<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Incident;
use App\Models\IssueType;
use App\Enums\IssueStatus;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ReportedIncidentsTable extends DataTableComponent
{
    protected $model = Incident::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('incident_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.relations.incidents.show", [
                'incident' => $row->incident_id,
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('reported-incidents');
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
                'class' => $columnIndex === 0 ? 'text-md-start border-end sticky' : 'text-md-center',
            ];
        });
    }

    private function getTypeOptions()
    {
        return IssueType::query()
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->issue_type_id => $item->issue_type_name];
            })->toArray();
    }

    public function builder(): Builder
    {
        return Incident::query()
            ->with([
                'types',
                'reportedBy',
                'reportedBy.account',
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Reporter'))
                ->label(function ($row) {
                    $name = Str::headline($row->reportedBy->full_name);
                    $photo = $row->reportedBy->account->photo;
                    $id = $row->reportedBy->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(function (Builder $query, $direction) {
                    return $query->join('employees', 'employees.employee_id', '=', 'incidents.reporter')
                        ->orderBy('employees.last_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('reportedBy', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                }),

            Column::make(__('Incident Type'))
                ->label(fn ($row) => $row->types->pluck('issue_type_name')->implode(', ')),

            Column::make(__('Status'))
                ->label(fn ($row) => view('components.status-badge')->with([
                    'color' => IssueStatus::from($row->status)->getColor(),
                    'slot' => IssueStatus::from($row->status)->getLabel(),
                ])),

            Column::make(__('Date Created'))
                ->label(fn ($row) => $row->created_at->format('F d, Y g:i A'))
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('created_at', $direction)),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Type'))
                ->options($this->getTypeOptions())
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('types', function ($subQuery) use ($value) {
                        $subQuery->where('issue_tags.issue_type_id', $value);
                    });
                }),
        ];
    }
}

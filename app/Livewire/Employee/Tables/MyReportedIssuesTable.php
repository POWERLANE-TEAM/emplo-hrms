<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\IssueConfidentiality;
use App\Enums\IssueStatus;
use App\Models\Issue;
use App\Models\IssueType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class MyReportedIssuesTable extends DataTableComponent
{
    protected $model = Issue::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('issue_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.relations.issues.show", [
                'issue' => $row->issue_id,
            ]))
            ->setTableRowUrlTarget(fn () => '__blank');
        $this->setPageName('my-reported-issues');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
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

    private function getConfidentialityOptions()
    {
        return array_reduce(IssueConfidentiality::cases(),
            fn ($options, $case) => $options + [$case->value => $case->getLabel()], []);
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
        return Issue::query()
            ->with([
                'types',
            ])
            ->where('issue_reporter', Auth::user()->account->employee_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Issue Type'))
                ->label(fn ($row) => $row->types->pluck('issue_type_name')->implode(', '))
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('types', function ($subQuery) use ($direction) {
                        $subQuery->orderBy('issue_type_name', $direction);
                    });
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('types', function ($subQuery) use ($searchTerm) {
                        $subQuery->whereLike('issue_type_name', "%{$searchTerm}%");
                    });
                }),

            Column::make(__('Confidentiality'))
                ->label(fn ($row) => IssueConfidentiality::from($row->confidentiality)->getLabel()),

            Column::make(__('Status'))
                ->label(fn ($row) => view('components.status-badge')->with([
                    'color' => IssueStatus::from($row->status)->getColor(),
                    'slot' => IssueStatus::from($row->status)->getLabel(),
                ])),

            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at)
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('filed_at', $direction)),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Confidentiality'))
                ->options($this->getConfidentialityOptions())
                ->filter(fn (Builder $query, $value) => $query->where('confidentiality', $value)),

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

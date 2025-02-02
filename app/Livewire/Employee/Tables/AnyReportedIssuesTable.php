<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Issue;
use App\Models\IssueType;
use App\Enums\IssueStatus;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use App\Enums\IssueConfidentiality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyReportedIssuesTable extends DataTableComponent
{
    protected $model = Issue::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('issue_id')
            ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.relations.issues.review", [
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
                'class' => $column->getTitle() === 'Reporter' ? 'text-md-start' : 'text-md-center',
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
                'reporter',
                'reporter.account',
            ])
            ->whereNot('issue_reporter', Auth::user()->account->employee_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Reporter'))
                ->label(function ($row) {
                    $name = Str::headline($row->reporter->full_name);
                    $photo = $row->reporter->account->photo;
                    $id = $row->reporter->employee_id;
            
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
                    return $query->join('employees', 'employees.employee_id', '=', 'issues.reporter')
                        ->orderBy('employees.last_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('reporter', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                }),

            Column::make(__('Issue Type'))
                ->label(fn ($row) => $row->types->pluck('issue_type_name')->implode(', ')),

            Column::make(__('Confidentiality'))
                ->label(fn ($row) => IssueConfidentiality::from($row->confidentiality)->getLabel()),

            Column::make(__('Status'))
                ->label(fn ($row) => IssueStatus::from($row->status)->getLabel()),
            
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
 
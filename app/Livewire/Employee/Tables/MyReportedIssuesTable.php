<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Issue;
use App\Enums\IssueStatus;
use App\Enums\IssueConfidentiality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class MyReportedIssuesTable extends DataTableComponent
{
    protected $model = Issue::class;

    public function configure(): void
    {
        $this->setPrimaryKey('issue_id');
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
        return Issue::query()
            ->with([
                'types'
            ])
            ->where('issue_reporter', Auth::user()->account->employee_id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Issue Type'))
                ->label(fn ($row) => $row->types->pluck('issue_type_name')->implode(', ')),

            Column::make(__('Confidentiality'))
                ->label(fn ($row) => IssueConfidentiality::from($row->confidentiality)->getLabel()),

            Column::make(__('Status'))
                ->label(fn ($row) => IssueStatus::from($row->status)->getLabel()),

            Column::make(__('Date Filed'))
                ->label(fn ($row) => $row->filed_at),
        ];
    }
}

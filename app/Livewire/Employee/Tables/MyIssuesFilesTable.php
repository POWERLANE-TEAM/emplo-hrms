<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Issue;
use App\Enums\FilePath;
use App\Http\Helpers\FileSize;
use Illuminate\Support\Carbon;
use App\Models\IssueAttachment;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class MyIssuesFilesTable extends DataTableComponent
{
    protected $model = IssueAttachment::class;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('attachment_id');
        $this->setPageName('my-issues-attachments');
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

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-6 py-1 text-secondary-emphasis fw-medium text-underline',
                    ]),
                    'heading' => __('Some file extensions may not be available for preview.'),
                ],
            ],
        ]);
    }

    public function builder(): Builder
    {
        return IssueAttachment::query()
            ->with([
                'issue',
            ])
            ->whereHas('issue', function ($query) {
                $query->where('issue_reporter', Auth::user()->account->employee_id);
            });
    }

    public function columns(): array
    {
        return [
            LinkColumn::make(__('File Name'))
                ->title(fn ($row) => $row->attachment_name)
                ->location(fn ($row) => route("{$this->routePrefix}.relations.issues.attachments.show", [
                    'attachment' => $row->attachment,
                ]))
                ->attributes(fn ($row) => [
                    'target' => '__blank',
                    'class' => 'text-primary',
                    'alt' => "{$row->attachment_name}",
                ])
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('attachment_name', $direction))
                ->searchable(fn (Builder $query, $searchTerm) => $query->whereLike('attachment_name', "%{$searchTerm}%")),

            LinkColumn::make('Link Issue')
                ->title(fn () => 'Click here to visit')
                ->location(fn ($row) => route("{$this->routePrefix}.relations.issues.show", [
                    'issue' => $row->issue->issue_id,
                ]))
                ->attributes(fn ($row) => [
                    'class' => 'text-primary',
                ]),

            Column::make(__('Date Submitted'))
                ->label(fn ($row) => $row->issue->filed_at)
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(
                        Issue::select('filed_at')
                            ->whereColumn('issues.issue_id', 'issue_attachments.issue_id'),
                        $direction
                    );
                })
                ->setSortingPillDirections('Oldest', 'Latest'),
            
            Column::make(__('Size'))
                ->label(function ($row) {
                    $path = FilePath::ISSUES->value . '/' . $row->attachment;
                    $sizeInBytes = Storage::disk('local')->size($path);

                    return FileSize::formatSize($sizeInBytes);
                }),

            Column::make(__('Last Modified'))
                ->label(function ($row) {
                    $path = FilePath::ISSUES->value.'/'.$row->attachment;
                    $lastModified = Storage::disk('local')->lastModified($path);
                    $convertFormat = Carbon::createFromTimestamp($lastModified, config('app.timezone'))->format('F d, Y g:i A');

                    return $convertFormat;
                }),
        ];
    }

    public function filters(): array
    {
        return [
            DateRangeFilter::make(__('Date Submitted Range'))
                ->config([
                    'allowInput' => true,
                    'altFormat' => 'F j, Y',
                    'ariaDateFormat' => 'F j, Y',
                    'dateFormat' => 'Y-m-d',
                    'latestDate' => now(),
                    'placeholder' => 'Enter Date Range',
                    'locale' => 'en',
                ])
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $query, array $dateRange) {
                    return $query->whereHas('issue', function ($subQuery) use ($dateRange) {
                        $subQuery->whereDate('filed_at', '>=', $dateRange['minDate'])
                            ->whereDate('filed_at', '<=', $dateRange['maxDate']);
                    });
                }),
        ];
    }
}
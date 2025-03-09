<?php

namespace App\Livewire\Tables;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AnnouncementsTable extends DataTableComponent
{
    protected $model = Announcement::class;

    public function configure(): void
    {
        $this->setPrimaryKey('announcement_id');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('published_at', 'desc');
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
                'class' => $column->getTitle() === 'Publisher' ? 'text-md-start' : 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return Announcement::query()
            ->with([
                'publisher' => [
                    'account',
                ],
                'offices',
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Title'))
                ->label(fn ($row) => $row->announcement_title),

            Column::make(__('Description'))
                ->label(fn ($row) => $row->announcement_description)
                ->deselected(),

            Column::make(__('Publisher'))
                ->label(function ($row) {
                    $name = Str::headline($row->publisher->full_name);
                    $photo = $row->publisher->account->photo;
                    $id = $row->publisher->employee_id;

                    return '<div class="d-flex align-items-center">
                                <img src="'.e($photo).'" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>'.e($name).'</div>
                                    <div class="text-muted fs-6">Employee ID: '.e($id).'</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name', $direction))
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('publisher', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%")
                            ->orWhereHas('account', fn ($query) => $query->orWhereLike('email', "%{$searchTerm}%"));
                    });
                }),

            Column::make(__('Published To'))
                ->label(function ($row) {
                    return $row->offices->map(function ($office) {
                        return $office->job_family_name;
                    })->join(', ');
                }),

            Column::make(__('Published At'))
                ->label(fn ($row) => Carbon::make($row->published_at)->format('F d, Y g:i A')),
        ];
    }
}

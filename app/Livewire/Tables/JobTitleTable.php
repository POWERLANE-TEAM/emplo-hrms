<?php

namespace App\Livewire\Tables;

use App\Models\JobTitle;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class JobTitleTable extends DataTableComponent
{
    protected $model = JobTitle::class;

    public function configure(): void
    {
        $this->setPrimaryKey('job_title_id');
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
                'class' => 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return JobTitle::query()
            ->with([
                'department',
                'jobFamily',
                'jobLevel',
                'employees',
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Job Title'))
                ->label(fn ($row) => $row->job_title),

            Column::make(__('Job Level'))
                ->label(function ($row) {
                    $level = $row->jobLevel;

                    return "Level {$level->job_level}: {$level->job_level_name}";
                }),

            Column::make(__('Job Family'))
                ->label(fn ($row) => $row->jobFamily->job_family_name),

            Column::make(__('Department'))
                ->label(fn ($row) => $row->department->department_name),

            Column::make(__('Total Employees'))
                ->label(fn ($row) => $row->employees->count()),

            // Column::make(__('Skill Qualifications'))
            //     ->label(function ($row) {
            //         return $row->skills->map(function ($skill) {
            //             return $skill->keyword;
            //         })->join(', ');
            //     }),

            // Column::make(__('Experience Qualifications'))
            //     ->label(function ($row) {
            //         return $row->experiences->map(function ($exp) {
            //             return $exp->keyword;
            //         })->join(', ');
            //     }),

            // Column::make(__('Education Qualifications'))
            //     ->label(function ($row) {
            //         return $row->educations->map(function ($education) {
            //             return $education->keyword;
            //         })->join(', ');
            //     }),
        ];
    }
}

<?php

namespace App\Livewire\Tables\HRManager;

use App\Models\Applicant;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ApplicantsTable extends PowerGridComponent
{
    public string $tableName = 'applicants-table-adsx9a-table';
    public string $primaryKey = 'applicants.applicant_id';
    public string $sortField  = 'applicants.applicant_id';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Applicant::query()->with('application', 'application.vacancy.jobTitle');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('full_name', fn($applicant) => $applicant->getFullNameAttribute())
            ->add('job_title', fn(Applicant $applicant) => $applicant->application->vacancy->jobTitle->job_title)
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Full Name', 'full_name')
                ->sortable()
                ->searchable(),

            Column::make('Job Position', 'job_title')
                ->sortable()
                ->searchable(),

            Column::make('Date Applied', 'created_at')
                ->sortable()
                ->searchable(),

            // Column::action('')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    // public function actions(Applicant $row): array
    // {
    //     return [
    //         null,
    //         // Button::add('edit')
    //         //     ->slot('Edit: ' . $row->id)
    //         //     ->id()
    //         //     ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
    //         //     ->dispatch('edit', ['rowId' => $row->id])
    //     ];
    // }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}

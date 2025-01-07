<?php

namespace App\Livewire\Employee\Tables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\ProbationaryPerformance;

class MyPerformancesAsProbationaryTable extends DataTableComponent
{
    protected $model = ProbationaryPerformance::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Probationary performance id", "probationary_performance_id")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}

<?php

namespace App\Livewire\Performances\Regular;

use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\PipPlan;
use App\Models\RegularPerformance;
use App\Models\RegularPerformancePeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Implemented Methods:
 *
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class ImprovementPlanTable extends DataTableComponent
{
    use DefaultTableConfig;

    protected $model = PipPlan::class;

    /**
     * @var array contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    #[Locked]
    public $routePrefix;

    public function configure(): void
    {
        $this->setPrimaryKey('pip_id')
            ->setTableRowUrl(function ($row) {
                $empStatus = strtolower($row->regularPerformance->employeeEvaluatee->status->emp_status_name);

                return route($this->routePrefix.".performances.plan.improvement.$empStatus.generated", $row);
            })
            ->setTableRowUrlTarget(function ($row) {

                return 'navigate';
            });

        $this->setTimezone();

        $this->configuringStandardTableMethods();

        $this->setConfigurableAreas([
            // 'toolbar-left-start' => [
            //     'components.table.filter.select-filter',
            //     [
            //
            //     ],
            // ],
        ]);
    }

    public function columns(): array
    {
        return [

            Column::make(__('Employee Evaluated'))
                ->label(
                    function ($row) {

                        $performanceForm = RegularPerformance::find($row->regular_performance_id);

                        if (! $performanceForm) {
                            report('Performance form not found');

                            return '--';
                        }

                        return $performanceForm->employeeEvaluatee->fullname;
                    },
                )
                ->sortable(),

            Column::make(__('Evaluation Year'), '')
                ->label(function ($row) {
                    $performanceForm = RegularPerformance::find($row->regular_performance_id);

                    $performancePeriod = RegularPerformancePeriod::find($performanceForm->period)->first();

                    return Carbon::parse($performancePeriod->start_date)->setTimezone($this->timezone)->format('Y');
                }),

            Column::make(__('Generation Date'), 'generated_at')
                ->label(
                    function ($row) {
                        return $row->generated_at->format('m/d/Y');
                    }

                )
                ->sortable(),

            Column::make('Modified at', 'modified_at')
                ->sortable()
                ->deselected(),
        ];
    }

    public function builder(): Builder
    {
        return PipPlan::query()
            ->select('*');
    }

    public function filters(): array
    {
        return [

        ];
    }

    /**
     * |--------------------------------------------------------------------------
     * | Column Searchable Queries
     * |--------------------------------------------------------------------------
     * Description
     */
}

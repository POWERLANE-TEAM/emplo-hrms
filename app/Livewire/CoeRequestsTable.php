<?php

namespace App\Livewire;

use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Carbon\Carbon;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\CoeRequest;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;

/**
 * Implemented Methods:
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class CoeRequestsTable extends DataTableComponent
{
    use DefaultTableConfig;

    #[Locked]
    public $routePrefix;

    protected $model = CoeRequest::class;

    /**
     * @var array $customFilterOptions contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function configure(): void
    {
        $this->setPrimaryKey('coe_request_id')
        ->setTableRowUrl(fn ($row) => route("{$this->routePrefix}.separation.coe.request", $row))
        ->setTableRowUrlTarget(fn () => '__blank');

        $this->configuringStandardTableMethods();

        $this->setConfigurableAreas([
            // 'toolbar-left-start' => [
            //     'components.table.filter.select-filter',
            //     [
            //         'filter' => (function () {

            //             //populate custom filter options here

            //             $this->customFilterOptions = [

            //             ];

            //             return SelectFilter::make('setFilterName', 'set FilterKey here')
            //                 ->options($this->customFilterOptions)
            //                 ->filter(function (Builder $builder, string $value) {
            //                     if ($value !== '') {
            //                         $this->dispatch('setFilter', 'set FilterKey here', $value);
            //                     }
            //                 })
            //                 ->setFirstOption('set Default Selected');
            //         })(),
            //     ],
            // ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__("Full Name"))
            ->label(function($row) {
                return $row->requestor->full_name;
            })
                ->sortable(),

            Column::make(__("Status"))
            ->label(function($row) {
                return $row->generated_by ? 'Issued' : 'Pending';
            })
                ->sortable(),

            Column::make("Deadline")
            ->label(function($row) {
                return Carbon::parse($row->created_at)->addDays(15)->format('F j, Y');
            })
                ->sortable(),

            Column::make("Updated at")
            ->label(function($row) {

                if($row->updated_at == $row->created_at){
                    return '--';
                }
               return  $row->updated_at->format('F j, Y');
            })
                ->sortable(),
        ];
    }


    public function builder(): Builder
    {
        $query = CoeRequest::query()
        ->with(['requestor'])
            ->select(
                '*'
            );

        return $query;
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

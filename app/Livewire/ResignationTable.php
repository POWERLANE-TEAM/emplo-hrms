<?php

namespace App\Livewire;

use App\Enums\EmploymentStatus;
use App\Enums\FilePath;
use App\Enums\ResignationStatus;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use App\Models\Resignation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;

/**
 * Implemented Methods:
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class ResignationTable extends DataTableComponent
{
    use DefaultTableConfig;

    protected $model = Employee::class;

    #[Locked]
    public string $routePrefix;

    /**
     * @var array $customFilterOptions contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function configure(): void
    {
        $this->setPrimaryKey('resignation_id')
            ->setTableRowUrl(function ($row) {
                return route($this->routePrefix . ".separation.resignations.review", $row);
            });

        $this->configuringStandardTableMethods();

        $this->setTimezone();

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
            Column::make("Full Name")
                ->label(fn($row) => $row->resignationLetter->employee->full_name)
                ->sortable(),
            Column::make("Status")
                ->label(
                    function ($row) {

                        // dd($row);

                        $status = $row->resignationStatus->resignation_status_name;

                        if ($status ==  strtolower(ResignationStatus::APPROVED->label()) && $row->resignationLetter->employee == EmploymentStatus::RESIGNED) {
                            $status = EmploymentStatus::RESIGNED->label();
                        }else if ($row->retracted_at) {
                            if($status !=  strtolower(ResignationStatus::PENDING->label())){
                                report('Resignation error');
                            }
                            $status = 'Retracted';
                        }

                        if ($status ==  strtolower(ResignationStatus::REJECTED->label())) {
                            $color = 'danger';
                        } else if ($status ==  strtolower(ResignationStatus::APPROVED->label())) {
                            $color = 'success';
                        } else
                         {
                            $color = 'info';
                        }

                        return view('components.status-badge')
                            ->with([
                                'color' => $color,
                                'slot' => $status,
                            ]);
                    }
                ),
            Column::make("Submitted On")
                ->label(function ($row) {
                    $filedAt = Carbon::parse($row->filed_at)->setTimezone($this->timezone);

                    return $filedAt->format('F d, Y');
                })
                ->sortable(),

            Column::make("Determined On")
                ->label(function ($row) {

                    $decisionAt = $row->finalDecisionAt;

                    if (!$decisionAt) {
                        return '--';
                    }

                    $decisionAtF = Carbon::parse($decisionAt)->setTimezone($this->timezone)->format('F d, Y');

                })
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return Resignation::query()
        // ->with('resignationLetter.employee.lifecycle', 'resignationStatus')
            /* ->select('*') */;
    }

    public function filters(): array
    {
        return [
            // DateFilter::make('')
            //     ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
            //     ->config([
            //         'min' => (function () {
            //             // set or fetch min date
            //             // return $minDate ? Carbon::parse($minDate)->format('Y-m-d') : now()->format('Y-m-d');
            //         })(),
            //         'max' => now()->format('Y-m-d'),
            //         'pillFormat' => 'd M Y',
            //         'placeholder' => 'Enter Date',
            //     ])
            //     ->filter(function (Builder $builder, string $value) {
            //         return;
            //     }),

            // SelectFilter::make('', 'set FilterKey here')
            //     ->options($this->customFilterOptions)
            //     ->filter(function (Builder $builder, string $value) {
            //         return $query = $builder->where('',  $value);
            //     })->hiddenFromMenus(),

        ];
    }

    /**
     * |--------------------------------------------------------------------------
     * | Column Searchable Queries
     * |--------------------------------------------------------------------------
     * Description
     */
}

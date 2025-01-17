<?php

namespace App\Livewire\Tables\Performance;

use App\Enums\EmploymentStatus;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Implemented Methods:
 *
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class EvaluationTable extends DataTableComponent
{
    use DefaultTableConfig;

    protected $model = Employee::class;

    public $routePrefix;

    public $employeeStatus;

    /**
     * @var array contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function mount(string $routePrefix, string $employeeStatus)
    {
        $this->routePrefix = $routePrefix;
        $this->employeeStatus = $employeeStatus;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id');

        $this->configuringStandardTableMethods();

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {

            if ($columnIndex == 0) {
                // Full name column
                return [
                    'class' => 'text-md-center border-end sticky',
                ];
            }

            if ($columnIndex == 2) {
                // Result  column
                // Log::info($row->result);
                return [
                    'class' => 'text-md-center text-capitalize',
                ];
            }

            if (in_array($columnIndex, [1, 2, 4])) {
                // Status and Performance Scale column
                return [
                    'class' => 'text-md-center text-capitalize',
                ];
            }

            return [
                'class' => 'text-md-center',
            ];
        });

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.table.filter.select-filter',
                [
                    'filter' => (function () {

                        // This is just temporary option for the dropdown
                        $currentMonth = now()->month;
                        $currentYear = now()->year;

                        if ($currentMonth <= 6) {
                            $evaluationPeriod = "January {$currentYear} - June {$currentYear}";
                        } else {
                            $evaluationPeriod = "July {$currentYear} - December {$currentYear}";
                        }

                        $this->customFilterOptions = [
                            '' => 'Select',
                            $evaluationPeriod => $evaluationPeriod,
                        ];

                        return SelectFilter::make('Evalutation Period', 'eval-period')
                            ->options($this->customFilterOptions)
                            ->filter(function (Builder $builder, string $value) {
                                if ($value !== '') {
                                    $this->dispatch('setFilter', 'eval-period', $value);
                                }
                            })
                            ->setFirstOption('Select');
                    })(),

                    'label' => 'Evaluation Period: ',
                ],
            ],

            'before-pagination' => [
                'components.buttons.download-btn',
                ['slot' => 'Download', 'attributes' => new ComponentAttributeBag(['class' => 'btn-primary'])],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            LinkColumn::make('Full Name')
                ->title(fn ($row) => $row->fullname)
                // route link should be changed to the correct route
                ->location(fn ($row) => route($this->routePrefix.'.performance.evaluation.index', ['employeeStatus' => $this->employeeStatus]))
                ->attributes(fn ($row) => [
                    'class' => 'fw-bold',
                    'style' => 'color: inherit',
                    'wire:navigate' => '',
                ])
                ->sortable(function ($query, $direction) {
                    return $query->orderBy('last_name', $direction)
                        ->orderBy('first_name', $direction)
                        ->orderBy('middle_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    $this->applyFullNameSearch($query, $searchTerm);
                })
                ->excludeFromColumnSelect(),

            Column::make('Status')
                ->label(function ($row) {
                    return rand(0, 1) ? 'complete' : 'pending';
                }),

            Column::make('Result')
                ->label(function ($row) {
                    return rand(0, 1) ? 'approved' : 'declined';
                }),

            Column::make('Final Rating')
                ->label(function ($row) {
                    return number_format(rand(0, 50) / 10, 1);
                }),

            Column::make('Performance Scale')
                ->label(function ($row) {
                    $values = ['exceeds expectation', 'outstanding', 'needs improvement', 'meets expectation'];

                    return $values[array_rand($values)];
                }),
        ];
    }

    public function builder(): Builder
    {
        $query = Employee::query()->with(['performances.categoryRatings', 'status', 'jobTitle.department', 'specificArea'])

            ->when($this->employeeStatus, function ($query) {
                $query->whereHas('status', function ($query) {
                    $query->where('employment_statuses.emp_status_id', '=', EmploymentStatus::findByLabel($this->employeeStatus));
                });
            })

            // prevent duplicate rows (workaround)
            ->distinct('employee_id');

        // Maybe add specific area restriction based on permission?

        // $this->limitSpecificArea($query);

        if (! $this->getAppliedFilterWithValue('attendance-date')) {
            //
        }

        return $query;
    }

    public function filters(): array
    {
        return [
            DateFilter::make('')
                ->setPillsLocale(in_array(session('locale'), explode(',', env('APP_SUPPORTED_LOCALES', 'en'))) ? session('locale') : env('APP_LOCALE', 'en'))
                ->config([
                    'min' => (function () {
                        // set or fetch min date
                        // return $minDate ? Carbon::parse($minDate)->format('Y-m-d') : now()->format('Y-m-d');
                    })(),
                    'max' => now()->format('Y-m-d'),
                    'pillFormat' => 'd M Y',
                    'placeholder' => 'Enter Date',
                ])
                ->filter(function (Builder $builder, string $value) {}),

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

    /**
     * Apply a date search filter to the query.
     *
     * This method normalizes the search term by removing spaces and then applies
     * a series of `orWhereRaw` conditions to the query to match the `created_at`
     * field of the table against various date formats.
     *
     * Supported date formats:
     * - 'YYYY-MM-DD'
     * - 'MM/DD/YYYY'
     * - 'MM-DD-YYYY'
     * - 'Month DD, YYYY'
     * - 'Month YYYY'
     * - 'Month'
     * - 'DD-MM-YYYY'
     * - 'DD/MM/YYYY'
     *
     * @param  \Illuminate\Database\Query\Builder  $query  The query builder instance.
     * @param  string  $searchTerm  The search term to filter by.
     * @return \Illuminate\Database\Query\Builder The modified query builder instance.
     */
    // public function applyDateSearch(Builder $query, $searchTerm)
    // {
    //     $normalizedSearchTerm = str_replace(' ', '', $searchTerm);
    //     return $query->orWhereRaw("replace(to_char(created_at, 'YYYY-MM-DD'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'MM/DD/YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'MM-DD-YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'Month DD, YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'Month YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'Month'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'DD-MM-YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"])
    //         ->orWhereRaw("replace(to_char(created_at, 'DD/MM/YYYY'), ' ', '') ILIKE ?", ["%{$normalizedSearchTerm}%"]);
    // }
}

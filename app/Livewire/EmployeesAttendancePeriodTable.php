<?php

namespace App\Livewire;

use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

/**
 * Implemented Methods:
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class EmployeesAttendancePeriodTable extends DataTableComponent
{
    use DefaultTableConfig;

    protected $model = Employee::class;

    /**
     * @var array $customFilterOptions contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id');

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
            Column::make("Employee")
                ->label(fn($row) => $row->fullname)
                ->sortable(),

            Column::make("Total Hours")
                ->label(function ($row) {
                    $totalSeconds = $row->attendanceLogs->sum(function ($log) {
                        return strtotime($log->timestamp);
                    });

                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;

                    return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
                })
                ->sortable(),
            Column::make("Regular Hours")
                ->label(function ($row) {
                    $totalSeconds = 36000; // Dummy data: 10 hours, 0 minutes, 0 seconds

                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;

                    return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
                })
                ->sortable(),

            Column::make("Rest Day Hours")
                ->label(function ($row) {
                    $totalSeconds = 7200; // Dummy data: 2 hours, 0 minutes, 0 seconds

                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;

                    return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
                })
                ->sortable(),

            Column::make("Regular Holiday")
                ->label(function ($row) {
                    $totalSeconds = 14400; // Dummy data: 4 hours, 0 minutes, 0 seconds

                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;

                    return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
                })
                ->sortable(),

            Column::make("Special Holiday")
                ->label(function ($row) {
                    $totalSeconds = 18000; // Dummy data: 5 hours, 0 minutes, 0 seconds

                    $hours = floor($totalSeconds / 3600);
                    $minutes = floor(($totalSeconds % 3600) / 60);
                    $seconds = $totalSeconds % 60;

                    return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
                })
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->with(['attendanceLogs'])
            ->select('employee_id', 'first_name', 'middle_name', 'last_name');
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
     * @param \Illuminate\Database\Query\Builder $query The query builder instance.
     * @param string $searchTerm The search term to filter by.
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

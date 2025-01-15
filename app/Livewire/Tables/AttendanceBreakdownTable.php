<?php

namespace App\Livewire\Tables;

use App\Enums\BiometricPunchType;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Attributes\Computed;
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
class AttendanceBreakdownTable extends DataTableComponent
{
    use DefaultTableConfig;

    protected $model = AttendanceLog::class;

    /**
     * @var array contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public $employee;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('uid');

        $this->configuringStandardTableMethods();

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag(['class' => 'fs-4 fw-bold']),
                    'heading' => 'Workdays Logs Breakdown',
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            // Column::make("Employee id")
            //     ->label(fn($row) => 'Sample')
            //     ->sortable(),
            Column::make('Date')
                ->sortable()
                ->label(fn ($row) => Carbon::parse($row->shift_date)->format('F d, Y')),
            Column::make('Time Recorded')
                ->label(function ($row) {
                    $checkIn = $row->check_in_time ? Carbon::parse($row->check_in_time)->format('h:i A') : 'No Check-In';
                    $checkOut = $row->check_out_time ? Carbon::parse($row->check_out_time)->format('h:i A') : 'No Check-Out';

                    return "$checkIn - $checkOut";
                })
                ->sortable(),
            Column::make('Duration')
                ->label(function ($row) {
                    if ($row->check_in_time && $row->check_out_time) {
                        $checkIn = Carbon::parse($row->check_in_time);
                        $checkOut = Carbon::parse($row->check_out_time);

                        if ($checkOut->lessThan($checkIn)) {
                            $checkOut->addDay();
                        }

                        $durationInSeconds = abs($checkOut->diffInSeconds($checkIn));
                        $hours = floor($durationInSeconds / 3600);
                        $minutes = floor(($durationInSeconds / 60) % 60);
                        $seconds = $durationInSeconds % 60;

                        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                    }

                    return 'N/A';
                })
                ->sortable(),

            Column::make('Check-Out Type')
                ->label(function ($row) {

                    $employeeId = $this->employee->employee_id;

                    if ($row->check_out_time) {
                        $checkOutType = AttendanceLog::where('timestamp', $row->check_out_time)
                            ->where('employee_id', $employeeId)
                            ->value('type');

                        return in_array($checkOutType, [BiometricPunchType::CHECK_OUT->value]) ? 'Regular' : 'Overtime';
                    }

                    return 'N/A';
                })
                ->sortable(),
        ];
    }

    #[Computed]
    public function getNightShift()
    {
        $nightShift = Shift::where('shift_name', 'Night Differential')->first();

        if (! $nightShift) {
            throw new \Exception('Night Differential shift not found');
        }

        $startTime = $nightShift->start_time;
        $endTime = $nightShift->end_time;

        return [$startTime, $endTime];
    }

    public function builder(): Builder
    {

        [$startTime, $endTime] = $this->getNightShift();

        $employeeId = $this->employee->employee_id;

        $query = AttendanceLog::query()
            ->where('employee_id', $employeeId)
            ->selectRaw("
                DATE(
                    CASE
                        WHEN timestamp::time >= '$startTime'
                            THEN timestamp
                        WHEN timestamp::time < '$endTime'
                            THEN timestamp - INTERVAL '1 day'
                        ELSE timestamp
                    END
                ) as shift_date,
                MIN(CASE WHEN type IN (0, 4) THEN timestamp END) as check_in_time,
                MAX(CASE WHEN type IN (1, 5) THEN timestamp END) as check_out_time
            ")
            ->groupBy('shift_date');

        // dd($query->limit(5)->get());

        return $query;
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

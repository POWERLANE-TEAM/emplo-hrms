<?php

namespace App\Livewire;

use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use App\Models\ApplicationDoc;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

/**
 * Implemented Methods:
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class EmployeeDocumentsTable extends DataTableComponent
{
    use DefaultTableConfig;

    public Employee $employee;

    protected $routePrefix;

    protected $model = ApplicationDoc::class;

    /**
     * @var array $customFilterOptions contains the dropdown values and keys.
     */
    protected $customFilterOptions;

    public function boot(): void
    {
        try {
            $this->employee->load('jobDetail');
            $this->setTimezone();
            $this->routePrefix = auth()->user()->account_type;
        } catch (\Throwable $th) {
            report($th);
        }
    }


    public function configure(): void
    {
        $this->setPrimaryKey('application_doc_id');

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
            Column::make("Document Name")
                ->label(function ($row) {
                    return ucwords($row->preemp_req_name);
                }),

            Column::make("File Name", 'file_path')
                ->format(function ($row) {
                    try {
                        $file_path = $row->file_path;

                        if (Storage::exists($file_path)) {
                            $file_name = basename($file_path);
                            return "<button data-bs-target=\"#modal-employee-application-doc\">$file_name</button>";
                        } else {

                            throw new \Exception('File not found');
                        }
                    } catch (\Throwable $th) {
                        report($th);
                        return '<span class="text-danger" >File not found.</span>';
                    }
                })
                ->html(),

            Column::make("Date Uploaded")
                ->label(function ($row) {
                    try {
                        if (is_null($row->submitted_at)) {
                            throw new \Exception('Submitted date is null');
                        }

                        return Carbon::parse($row->submitted_at)->setTimezone($this->timezone)->format('d/m/y');
                    } catch (\Exception $e) {
                        report($e);
                        return '--';
                    }
                }),

            LinkColumn::make("History")
                ->title(fn($row) => 'See History')
                // Route to the view > work hours
                // use routePrefix
                ->location(fn($row) => route($this->routePrefix . '.employees.information', ['employee' => $this->employee->employee_id]) . '/#information'),

        ];
    }

    public function builder(): Builder
    {
        $query = ApplicationDoc::query()

            ->where('application_id', $this->employee->jobDetail->application_id)
            ->select(
                'application_docs.application_doc_id',
                'application_docs.preemp_req_id',
                'application_docs.application_id',
                'application_docs.submitted_at',
                'application_docs.file_path',
                'preemp_requirements.preemp_req_id',
                'preemp_requirements.preemp_req_name'
            )
            ->join('preemp_requirements', 'application_docs.preemp_req_id', '=', 'preemp_requirements.preemp_req_id');


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

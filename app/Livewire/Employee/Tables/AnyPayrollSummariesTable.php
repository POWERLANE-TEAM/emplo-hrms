<?php

namespace App\Livewire\Employee\Tables;

use App\Exports\PayrollSummaryExport;
use App\Livewire\Tables\Defaults;
use App\Models\Payroll;
use App\Models\PayrollSummary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyPayrollSummariesTable extends DataTableComponent
{
    use Defaults;

    protected $model = PayrollSummary::class;

    private $payrollOptions;

    private $customFilterOptions;

    public function mount()
    {
        $this->payrollOptions = Payroll::query()
            ->latest('cut_off_start')
            ->get()
            ->mapWithKeys(fn ($payroll) => [
                $payroll->payroll_id => $payroll->cut_off,
            ])
            ->toArray();
    }

    public function exportAsExcel()
    {
        $payrollFilter = $this->getAppliedPayrollFilter();

        $cutOffEnd = Str::afterLast(implode(array_values($payrollFilter)), ' - ');

        return Excel::download(
            new PayrollSummaryExport(implode(array_keys($payrollFilter))),
            sprintf('psummary-%s.xlsx', Carbon::make($cutOffEnd)->format('mdy')),
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function exportAsCsv()
    {
        $payrollFilter = $this->getAppliedPayrollFilter();

        $cutOffEnd = Str::afterLast(implode(array_values($payrollFilter)), ' - ');

        return Excel::download(
            new PayrollSummaryExport(implode(array_keys($payrollFilter))),
            sprintf('psummary-%s.csv', Carbon::make($cutOffEnd)->format('mdy')),
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    private function getAppliedPayrollFilter()
    {
        return array_filter(
            $this->payrollOptions,
            fn ($filter) => $filter == $this->getAppliedFilterWithValue('payroll'),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function configure(): void
    {
        $this->setPrimaryKey('payroll_summary_id');
        $this->setPageName('payroll');
        $this->setDefaultSortingLabels('Lowest', 'Highest');
        $this->setSingleSortingDisabled();
        $this->setRememberColumnSelectionDisabled();

        $this->configuringStandardTableMethods();

        $this->payrollOptions = Payroll::query()
            ->latest('cut_off_start')
            ->get()
            ->mapWithKeys(fn ($payroll) => [
                $payroll->payroll_id => $payroll->cut_off,
            ])
            ->toArray();

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.table.filter.select-filter',
                [
                    'filter' => (function () {
                        return SelectFilter::make(__('Payroll'))
                            ->options($this->payrollOptions)
                            ->filter(function (Builder $query, $value) {
                                $this->dispatch('setFilter', 'payroll', $value);
                            })
                            ->setFirstOption('Latest');
                    })(),

                    'label' => __('Payroll Period: '),
                ],
            ],

            'toolbar-right-end' => 'components.table.filter.export',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start border-end sticky' : 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return PayrollSummary::query()
            ->with([
                'employee' => [
                    'account',
                ],
                'payroll',
            ])
            ->whereHas('employee', fn ($query) => $query->activeEmploymentStatus());
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->employee->full_name);
                    $photo = $row->employee->account->photo;
                    $id = $row->employee->employee_id;

                    return '<div class="d-flex align-items-center">
                                <img src="'.e($photo).'" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>'.e($name).'</div>
                                    <div class="text-muted fs-6">Employee ID: '.e($id).'</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(function (Builder $query, $direction) {
                    return $query->join('employees', 'employees.employee_id', '=', 'payroll_summaries.employee_id')
                        ->orderBy('last_name', $direction);
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('employee', function ($subQuery) use ($searchTerm) {
                        $subQuery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    });
                })
                ->setSortingPillDirections('A-Z', 'Z-A'),

            Column::make(__('Regular Hours Worked'))
                ->label(fn ($row) => $row->reg_hrs)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hrs', $direction)),

            Column::make(__('Regular Night Differential'))
                ->label(fn ($row) => $row->reg_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_nd', $direction)),

            Column::make(__('Regular Overtime'))
                ->label(fn ($row) => $row->reg_ot)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_ot', $direction)),

            Column::make(__('Regular Overtime Night Differential'))
                ->label(fn ($row) => $row->reg_ot_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_ot_nd', $direction)),

            Column::make(__('Rest Day Hours Worked'))
                ->label(fn ($row) => $row->rest_hrs)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('rest_hrs', $direction)),

            Column::make(__('Rest Day Night Differential'))
                ->label(fn ($row) => $row->rest_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('rest_nd', $direction)),

            Column::make(__('Rest Day Overtime'))
                ->label(fn ($row) => $row->rest_ot)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('rest_ot', $direction)),

            Column::make(__('Rest Day Overtime Night Differential'))
                ->label(fn ($row) => $row->rest_ot_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('rest_ot_nd', $direction)),

            Column::make(__('Regular Holiday Hours Worked'))
                ->label(fn ($row) => $row->reg_hol_hrs)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_hrs', $direction)),

            Column::make(__('Regular Holiday Night Differential'))
                ->label(fn ($row) => $row->reg_hol_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_nd', $direction)),

            Column::make(__('Regular Holiday Overtime'))
                ->label(fn ($row) => $row->reg_hol_ot)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_ot', $direction)),

            Column::make(__('Regular Holiday Overtime Night Differential'))
                ->label(fn ($row) => $row->reg_hol_ot_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_ot_nd', $direction)),

            Column::make(__('Regular Holiday Rest Day Hours Worked'))
                ->label(fn ($row) => $row->reg_hol_rest_hrs)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_rest_hrs', $direction)),

            Column::make(__('Regular Holiday Rest Day Night Differential'))
                ->label(fn ($row) => $row->reg_hol_rest_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_rest_nd', $direction)),

            Column::make(__('Regular Holiday Rest Day Overtime'))
                ->label(fn ($row) => $row->reg_hol_rest_ot)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_rest_ot', $direction)),

            Column::make(__('Regular Holiday Rest Day Overtime Night Differential'))
                ->label(fn ($row) => $row->reg_hol_rest_ot_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('reg_hol_rest_ot_nd', $direction)),

            Column::make(__('Special Holiday Hours Worked'))
                ->label(fn ($row) => $row->spe_hol_hrs)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_hrs', $direction)),

            Column::make(__('Special Holiday Night Differential'))
                ->label(fn ($row) => $row->spe_hol_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_nd', $direction)),

            Column::make(__('Special Holiday Overtime'))
                ->label(fn ($row) => $row->spe_hol_ot)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_ot', $direction)),

            Column::make(__('Special Holiday Overtime Night Differential'))
                ->label(fn ($row) => $row->spe_hol_ot_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_ot_nd', $direction)),

            Column::make(__('Special Holiday Rest Day Hours Worked'))
                ->label(fn ($row) => $row->spe_hol_rest_hrs)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_rest_hrs', $direction)),

            Column::make(__('Special Holiday Rest Day Night Differential'))
                ->label(fn ($row) => $row->spe_hol_rest_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_rest_nd', $direction)),

            Column::make(__('Special Holiday Rest Day Overtime'))
                ->label(fn ($row) => $row->spe_hol_rest_ot)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_rest_ot', $direction)),

            Column::make(__('Special Holiday Rest Day Overtime Night Differential'))
                ->label(fn ($row) => $row->spe_hol_rest_ot_nd)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('spe_hol_rest_ot_nd', $direction)),

            Column::make(__('Absence Days'))
                ->label(fn ($row) => $row->abs_days)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('abs_days', $direction)),

            Column::make(__('Undertime Hours'))
                ->label(fn ($row) => $row->ut_hours)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('ut_hours', $direction)),

            Column::make(__('Tardy Hours'))
                ->label(fn ($row) => $row->td_hours)
                ->sortable(fn (Builder $builder, $direction) => $builder->orderBy('td_hours', $direction)),

            Column::make(__('Payroll'))
                ->label(fn ($row) => $row->payroll->cut_off),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Payroll Period'), 'payroll')
                ->options($this->payrollOptions)
                ->filter(function (Builder $query, $value) {
                    $query->where('payroll_id', $value);
                })
                ->setFilterDefaultValue(Payroll::latest('cut_off_start')->first()->payroll_id)
                ->hiddenFromMenus(),
        ];
    }
}

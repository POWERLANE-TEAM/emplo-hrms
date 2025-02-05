<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\PayrollSummary;
use App\Livewire\Tables\Defaults;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class IndividualPayrollSummariesTable extends DataTableComponent
{
    use Defaults;

    public Employee $employee;

    protected $model = PayrollSummary::class;

    private $payrollOptions;

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
                $payroll->payroll_id => $payroll->cut_off
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

                    'label' => __('Payroll Period: ')
                ],
            ],

            'toolbar-right-end' => 'components.table.filter.export',
        ]);
        
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'text-md-center',
            ];
        });
    }

    public function builder(): Builder
    {
        return PayrollSummary::query()
            ->with([
                'employee' => fn ($query) => $query->select('employee_id'),
                'payroll',
            ])
            ->whereHas('employee', fn ($query) => $query->where('employee_id', $this->employee->employee_id));
    }

    public function columns(): array
    {
        return [
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

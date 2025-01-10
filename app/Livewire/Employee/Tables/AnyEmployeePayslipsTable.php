<?php

namespace App\Livewire\Employee\Tables;

use App\Models\Payroll as PayrollModel;
use App\Enums\Payroll;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyEmployeePayslipsTable extends DataTableComponent
{
    protected $model = Employee::class;

    public function configure(): void
    {
        $this->setPrimaryKey('employee_id');
        $this->setPageName('employee-payslips');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->enableAllEvents();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('created_at', 'desc');
        $this->setSearchDebounce(1000);
        $this->setTrimSearchStringEnabled();
        $this->setEmptyMessage(__('No results found.'));
        $this->setPerPageAccepted([10, 25, 50, 100, -1]);
        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);
        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-2']);

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition mx-4',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-3 search text-body body-bg shadow-sm',
        ]);

        $this->setThAttributes(function (Column $column) {
            return [
                'default' => true,
                'class' => 'text-center fw-medium',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start' : 'text-md-center',
            ];
        });

        $payroll = Payroll::getCutOffPeriod(now(), isReadableFormat: true);

        $this->setConfigurableAreas([
            'toolbar-left-start' => [
                'components.headings.main-heading',
                [
                    'overrideClass' => true,
                    'overrideContainerClass' => true,
                    'attributes' => new ComponentAttributeBag([
                        'class' => 'fs-6 py-1 text-secondary-emphasis fw-medium text-underline',
                    ]),
                    'heading' => __("Current Payroll: {$payroll['start']} - {$payroll['end']}"),
                ],
            ],
        ]);
    }
    
    public function builder(): Builder
    {
        return Employee::query()
            ->with([
                'payslips' => [
                    'payroll'
                ],
                'account',
                'status' => fn ($query) => $query->select('emp_status_name'),
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->full_name);
                    $photo = $row->account->photo;
                    $id = $row->employee_id;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(fn (Builder $query, $direction) => $query->orderBy('last_name' ,$direction))
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereLike('first_name', "%{$searchTerm}%")
                        ->orWhereLike('middle_name', "%{$searchTerm}%")
                        ->orWhereLike('last_name', "%{$searchTerm}%")
                        ->orWhereHas('account', fn ($query) => $query->orWhereLike('email', "%{$searchTerm}%"));
                }),

            Column::make(__('Payslip File'))
                ->label(fn ($row) => $row?->paylips?->first()?->attachment_name ?? ' - '),

            Column::make(__('Employment Status'))
                ->label(fn ($row) => $row->status->emp_status_name),

            // Column::make(__('Payroll'))
            //     ->label(fn ($row) => $row?->payslips?->first()?->payroll?->cut_off),

            // Column::make(__('Payout'))
            //     ->label(fn ($row) => $row?->payslips?->first()?->payroll?->payout),

            Column::make(__('Upload Section'))
                ->label(function ($row, Column $column) {
                    return 
                        "<button class='btn btn-primary fw-light px-4' 
                            wire:click=\"\$dispatchTo(
                                'employee.payslip.upload-payslip',
                                'uploadPayslip',
                                { employeeId: $row->employee_id },
                            )\"
                        >Upload
                        </button>";
                })
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make(__('Payroll Period'))
                ->options($this->getPayrollOptions())
                ->filter(function (Builder $query, $value) {
                    $query->whereHas('payslips', function ($subQuery) use ($value) {
                        $subQuery->orWhere('payroll_id', $value);
                    })->orDoesntHave('payslips');
                })
                ->setFilterDefaultValue(PayrollModel::latest('cut_off_start')->first()->payroll_id),
        ];
    }

    private function getPayrollOptions()
    {
        return PayrollModel::all()->mapWithKeys(function ($item) {
            return [$item->payroll_id => $item->cut_off];
        })->toArray();
    }
}

<?php

namespace App\Livewire\Employee\Tables;

use App\Enums\Payroll;
use App\Enums\FilePath;
use App\Models\Employee;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Http\Helpers\FileSize;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Models\Payroll as PayrollModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class AnyEmployeePayslipsTable extends DataTableComponent
{
    protected $model = Employee::class;

    #[Locked]
    public $routePrefix;

    private $period;

    private $filterPeriod;

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
        $this->setRememberColumnSelectionDisabled();

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
                'class' => $column->getTitle() === 'Employee' ? 'text-md-start ' : 'text-md-center',
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

    #[On('payslipUploaded')]
    public function refreshTableComponent()
    {
        $this->dispatch('refreshDatatable');
    }

    public function builder(): Builder
    {
        $this->period = request('table-filters')['payroll_period']
            ?? PayrollModel::latest('cut_off_start')->first()->payroll_id;

        return Employee::query()
            ->with([
                'payslips.payroll',
                'account',
                'status' => fn ($query) => $query->select('emp_status_name'),
            ]);
    }

    private function createEventPayload($row)
    {
        $payroll = $this->period;
        $employee = $row->employee_id;

        return compact('payroll', 'employee');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->full_name);
                    $photo = $row->account->photo;
                    $id = $row->employee_id;
                    $status = $row->status->emp_status_name;
            
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div>' . e($name) . '</div>
                                    <div class="text-muted fs-6">Employee ID: ' . e($id) . '</div>
                                    <small class="text-muted">' . e($status) . '</small>
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

            Column::make(__('File Name'))
                ->label(function ($row) {
                    return $row->payslips->firstWhere('payroll_id', $this->period)
                        ->attachment_name ?? ' - ';
                }),

            Column::make(__('File Size'))
                ->label(function ($row) {
                    $file = $row?->payslips?->firstWhere('payroll_id', $this->period)
                        ?->hashed_attachment;

                    if ($file) {
                        $path = sprintf('%s/%s', FilePath::PAYSLIPS->value, $file);
                        $sizeInBytes = Storage::disk('local')->size($path);
                        return FileSize::formatSize($sizeInBytes);                        
                    } else {
                        return ' - ';
                    }
                }),

            Column::make(__('Last Modified'))
                ->label(function ($row) {
                    $file = $row?->payslips?->firstWhere('payroll_id', $this->period)
                        ?->hashed_attachment;
                    
                    if ($file) {
                        $path = sprintf('%s/%s', FilePath::PAYSLIPS->value, $row->hashed_attachment);
                        $lastModified = Storage::disk('local')->lastModified($path);
                        $convertFormat = Carbon::createFromTimestamp($lastModified, config('app.timezone'))->format('F d, Y g:i A');
                        return $convertFormat;
                    } 
                    
                    return ' - ';
                }),

            Column::make(__('Action'))
                ->label(function ($row, Column $column) {
                    $fileExist = $row?->payslips?->firstWhere('payroll_id', $this->period);
                    $eventPayload = $this->createEventPayload($row);

                    if (! $fileExist) {
                        return 
                            "<button class='btn btn-primary fw-light px-4' 
                                wire:click=\"\$dispatchTo(
                                    'employee.payslip.upload-payslip',
                                    'uploadPayslip',
                                    { eventPayload: " . htmlspecialchars(json_encode($eventPayload), ENT_QUOTES, 'UTF-8') . "}
                                )\"
                            >Upload
                            </button>";
                    }

                    return __('Uploaded');
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
                    $query->with(['payslips' => function ($subQuery) use ($value) {
                        $subQuery->where('payroll_id', $value);
                    }])->first();

                    $this->period = (int) $value;
                })
                ->setFilterDefaultValue((int) $this->period),
        ];
    }    

    private function getPayrollOptions()
    {
        return PayrollModel::all()->mapWithKeys(function ($item) {
            return [$item->payroll_id => $item->cut_off];
        })->toArray();
    }
}

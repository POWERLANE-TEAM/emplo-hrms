<?php

namespace App\Livewire\Admin;

use App\Enums\BiometricPunchType;
use App\Http\Helpers\BiometricDevice;
use App\Models\AttendanceLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AttendanceLogsTable extends DataTableComponent
{
    protected $model = AttendanceLog::class;

    private BiometricDevice $zkInstance;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('attendance-logs');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('timestamp', 'desc');
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
            'class' => 'form-control rounded-pill search text-body body-bg',
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
    }

    // found this somewhere, alternative to boot is to resolve using service container.
    public function initialize()
    {
        if (! isset($this->zkInstance)) {
            $this->zkInstance = app(BiometricDevice::class);
        }
    }

    public function builder(): Builder
    {
        $this->initialize();

        return AttendanceLog::query()
            ->with([
                'employee',
                'employee.account',
            ])
            ->select('*');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Employee Id'), 'employee_id')
                ->sortable()
                ->searchable(),

            Column::make(__('Employee'))
                ->label(function ($row) {
                    $name = Str::headline($row->employee->full_name);
                    $photo = $row->employee->account->photo;

                    return '<div class="d-flex justify-content-center align-items-center">
                                <img src="'.e($photo).'" alt="User Picture" style="width: 33px; height: 33px; border-radius: 50%; margin-right: 10px;">
                                <span>'.e($name).'</span>
                            </div>';
                })
                ->html(),

            Column::make(__('Type'))
                ->label(function ($row) {
                    $type = BiometricPunchType::tryFrom($row->type);

                    return $type = $type ? $type->getDescription() : 'Not valid';
                }),

            Column::make(__('Time'), 'timestamp')
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => $row->timestamp->format('g:i A')),

            Column::make(__('Date'), 'timestamp')
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => $row->timestamp->format('F d, Y')),
        ];
    }
}

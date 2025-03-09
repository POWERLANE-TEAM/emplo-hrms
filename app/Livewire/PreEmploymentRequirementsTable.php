<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Livewire\Tables\Defaults as DefaultTableConfig;
use App\Models\Employee;
use App\Models\PreempRequirement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Js;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

/**
 * Implemented Methods:
 *
 * @method  configure(): void
 * @method  columns(): array
 * @method  builder(): Builder
 * @method  filters(): array
 */
class PreEmploymentRequirementsTable extends DataTableComponent
{
    use DefaultTableConfig;

    public Employee $employee;

    protected $routePrefix;

    private bool $isBasicEmployee;

    protected $model = PreempRequirement::class;

    public function configuring(): void
    {

        try {
            $this->isBasicEmployee = auth()->user()->hasRole(UserRole::BASIC);

            $this->employee->loadMissing('jobDetail');
            $this->setTimezone();
            $this->routePrefix = auth()->user()->account_type;

        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('preemp_req_id');

        $this->configuringStandardTableMethods();

        $this->setTrAttributes(function ($row, $index) {

            $attributes = [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition',
            ];

            if ($index + 1 == $this->perPage) {
                $attributes['x-intersect.full'] = 'updatePerPage()';
                $attributes['x-data'] = 'perPageDispatcher';
            }

            return $attributes;
        });

        if ($this->isBasicEmployee) {
            // $this->setPaginationDisabled();
            $this->setPaginationVisibilityDisabled();
            $this->setPerPageAccepted([5, 10, 15, 20, -1]);
            $this->setDefaultPerPage(10);
            $this->setPerPage(10);
        }
    }

    public function columns(): array
    {
        return [
            Column::make(__('Document Name'), 'preemp_req_name')
                ->sortable(),
            Column::make(__('Attachment'))
                ->label(function ($row) {
                    return __('Attachment');
                })
                ->label(function ($row, $column) {

                    $rowIndex = $column->getRowIndex();

                    $attachmentTxt = __('Attachment');

                    return <<<HTML
                    <button class="btn" onClick="openModal('preemp-form-{$rowIndex}')"> $attachmentTxt </button>
                HTML;
                })
                ->html()
                ->sortable(),
            Column::make('Upload')
                ->label(function ($row, $column) {
                    $rowIndex = $column->getRowIndex();

                    return <<<HTML
                    <button class="btn btn-primary" onClick="openModal('preemp-form-{$rowIndex}', () => {document.getElementById('preemp-form-{$rowIndex}').querySelector('input').click();});">Upload</button>
                HTML;
                })
                ->html()
                ->hideIf(! $this->isBasicEmployee),

            Column::make('Created at', 'created_at')
                ->deselected()
                ->sortable(),
            Column::make('Date Uploaded')
                ->label(function ($row) {
                    try {
                        if (is_null(optional($row->applicationDocs->first())->submitted_at)) {
                            throw new \Exception('Submitted date is null');
                        }

                        // dd ($row->applicationDocs->first());
                        return Carbon::parse(optional($row->applicationDocs->first())->submitted_at)->setTimezone($this->timezone)->format('d/m/y');
                    } catch (\Exception $e) {
                        report($e);

                        return '--';
                    }
                })
                ->deselectedIf($this->isBasicEmployee),

            LinkColumn::make('History')
                ->title(fn ($row) => 'See History')

                ->location(fn ($row) => route($this->routePrefix.'.employees.information', ['employee' => $this->employee->employee_id]).'/#information')
                ->deselectedIf($this->isBasicEmployee),
        ];
    }

    public function builder(): Builder
    {

        $query = PreempRequirement::query()
            ->whereNot('preemp_req_id', 17) // exclude resume
            ->with(['applicationDocs' => function ($query) {
                $query->where('application_id', $this->employee->jobDetail->application_id);
            }])
            ->select(
                'preemp_requirements.preemp_req_id',
                'preemp_requirements.preemp_req_name',
                // 'preemp_requirements.sample_file',
                'preemp_requirements.created_at',
                'preemp_requirements.updated_at',
            );

        // dd($query->orderBy('preemp_req_id', 'DESC')->limit(5)->get());

        return $query;
    }

    public function filters(): array
    {
        return [
            //
        ];
    }

    #[Js]
    public function openAttachment($id = null)
    {
        return <<<JS
                alert("$id");
            openModal("preemp-form-$id");
        JS;
    }
}

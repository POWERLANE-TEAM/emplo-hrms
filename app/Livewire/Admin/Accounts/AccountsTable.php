<?php

namespace App\Livewire\Admin\Accounts;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AccountsTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('user_id');
        $this->setPageName('accounts');
        $this->setEagerLoadAllRelationsEnabled();
        $this->setSingleSortingDisabled();
        $this->setQueryStringEnabled();
        $this->setSearchDebounce(1000);
        $this->setTrimSearchStringEnabled();
        $this->setOfflineIndicatorEnabled();
        $this->setDefaultSort('created_at', 'desc');
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
                'class' => 'text-start fw-medium',
            ];
        });

        // $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
        //     return [
        //         'class' => 'text-md-center',
        //     ];
        // });
    }

    public function columns(): array
    {
        return [
            Column::make(__('Full Name'))
                ->label(function ($row) {
                    $name = Str::headline($row->account->full_name);
                    $photo = $row->photo;
                    $email = $row->email;
            
                    // this is disgusting. Change this somehow
                    return '<div class="d-flex align-items-center">
                                <img src="' . e($photo) . '" alt="User Picture" class="rounded-circle me-3" style="width: 38px; height: 38px;">
                                <div>
                                    <div class="fw-bold">' . e($name) . '</div>
                                    <div class="text-muted" style="font-size: 0.9em;">' . e($email) . '</div>
                                </div>
                            </div>';
                })
                ->html()
                ->sortable(function (Builder $query, $direction) {
                    return $query->whereHas('account', function ($subquery) use ($direction) {
                        $subquery->orderBy('last_name', $direction);
                    });
                })
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->whereHas('account', function ($subquery) use ($searchTerm) {
                        $subquery->whereLike('first_name', "%{$searchTerm}%")
                            ->orWhereLike('middle_name', "%{$searchTerm}%")
                            ->orWhereLike('last_name', "%{$searchTerm}%");
                    })->orWhereLike('email', "%{$searchTerm}%");
                }),

            Column::make(__('Role'))
                ->label(function ($row) {
                    $role = UserRole::tryFrom($row->getRoleNames()->first());
                    return $role ? $role->label() : __('Not Applicable');
                }),

            Column::make(__('Type'))
                ->label(function ($row) {
                    $type = AccountType::tryFrom($row->account_type);
                    return $type ? $type->label() : $type;
                }),

            Column::make(__('Status'))
                ->label(function ($row) {
                    $status = UserStatus::tryFrom($row->status->user_status_id);
                    return $status ? $status->label() : $status;
                }),

            Column::make(__('Registered Date'), 'created_at')
                ->format(fn ($value, $row, Column $column) => Carbon::parse($row->created_at)->diffForHumans())
                ->sortable()
                ->searchable(),
                
            Column::make(__('2FA'), 'two_factor_confirmed_at')
                ->format(fn ($value, $row, Column $column)
                    => isset($row->two_factor_confimed_at)
                        ? __('Enabled')
                        : __('Disabled')
                )
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return User::query()
            ->with(['account', 'status', 'roles'])
            ->select('*');
    }
}

<?php

namespace App\Livewire\Tables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

trait Defaults
{
    public function configuringStandardTableMethods()
    {
        // Your standard configure() options go here, anything set here will be over-ridden by the configure() method
        // For Example
        $this->setEagerLoadAllRelationsEnabled();

        $this->setTableAttributes([
            'default' => true,
            'class' => 'table-hover px-1 no-transition',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline no-transition',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
        ]);

        $this->setTrimSearchStringEnabled();

        // $this->setSearchDebounce(1000);

        $this->setEmptyMessage(__('No results found.'));

        $this->setToolsAttributes(['class' => ' bg-body-secondary border-0 rounded-3 px-5 py-3']);

        $this->setToolBarAttributes(['class' => ' d-md-flex my-md-2']);

        $this->setPerPageAccepted([10, 25, 50, 100, -1]);

        $this->setThAttributes(function (Column $column) {

            return [
                'default' => true,
                'class' => 'text-center fw-normal',
            ];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            return [
                'class' => 'text-md-center',
            ];
        });
    }

    public function configuredStandardTableMethods()
    {
        // Your standard configure() options go here, anything set here will override those set in the configure() method
        // For Example
        // $this->setColumnSelectDisabled();
    }


    public function applyFullNameSearch(Builder $query, $searchTerm): Builder
    {
        $terms = explode(' ', $searchTerm);

        foreach ($terms as $term) {
            $query->orWhere(function ($query) use ($term) {
                $query->where('first_name', 'ILIKE', "%{$term}%")
                    ->orWhere('middle_name', 'ILIKE', "%{$term}%")
                    ->orWhere('last_name', 'ILIKE', "%{$term}%");
            });
        }

        return $query;
    }

    protected function limitSpecificArea($query)
    {
        $account = auth()->user()->account;

        if ($account && $account->specificArea) {
            $area = $account->specificArea->first();

            if ($area && $area->area_id != 2) {
                $query->where('specificArea.area_id', $area->area_id);
            }
        } else {
            report(new \Exception("User ID: auth()->user()->user_id  User  $account->fullName ; has no Specific Area Assigned"));
        }
    }
}

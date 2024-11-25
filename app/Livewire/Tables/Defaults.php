<?php

namespace App\Livewire\Tables;

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
            'class' => 'table-hover px-1',
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return [
                'default' => true,
                'class' => 'border-1 rounded-2 outline',
            ];
        });

        $this->setSearchFieldAttributes([
            'type' => 'search',
            'class' => 'form-control rounded-pill search text-body body-bg',
        ]);

        $this->setTrimSearchStringEnabled();

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
}

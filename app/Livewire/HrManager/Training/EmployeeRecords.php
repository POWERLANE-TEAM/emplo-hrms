<?php

namespace App\Livewire\HrManager\Training;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Locked;

class EmployeeRecords extends Component
{
    public Employee $employee;

    #[Locked]
    public $routePrefix;

    public $title;

    public $provider;

    public int|string $trainer;

    public $description;

    public $startDate;

    public $endDate;

    public function render()
    {
        return view('livewire.hr-manager.training.employee-records');
    }
}

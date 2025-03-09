<?php

namespace App\Livewire\HrManager\Employees;

use App\Models\Employee;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Payslips extends Component
{
    public Employee $employee;

    #[Locked]
    public $routePrefix;

    public function render()
    {
        return view('livewire.hr-manager.employees.payslips');
    }
}

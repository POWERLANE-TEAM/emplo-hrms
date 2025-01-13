<?php

namespace App\Livewire\HrManager\Employees;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Locked;

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

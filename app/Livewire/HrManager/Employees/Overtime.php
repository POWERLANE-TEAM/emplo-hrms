<?php

namespace App\Livewire\HrManager\Employees;

use App\Models\Employee;
use Livewire\Component;

class Overtime extends Component
{
    public Employee $employee;

    public function render()
    {
        return view('livewire.hr-manager.employees.overtime');
    }
}

<?php

namespace App\Livewire\HrManager\Employees;

use Livewire\Component;
use App\Models\Employee;

class Leaves extends Component
{
    public Employee $employee;

    public function render()
    {
        return view('livewire.hr-manager.employees.leaves');
    }
}

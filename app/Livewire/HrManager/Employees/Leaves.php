<?php

namespace App\Livewire\HrManager\Employees;

use App\Models\Employee;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy(isolate: false)]
class Leaves extends Component
{
    public Employee $employee;

    public function placeholder()
    {
        return view('livewire.placeholder.profile');
    }

    public function render()
    {
        return view('livewire.hr-manager.employees.leaves');
    }
}

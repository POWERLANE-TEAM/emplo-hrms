<?php

namespace App\Livewire\HrManager\Employees;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Lazy;

#[Lazy(isolate: false)]
class PayrollSummary extends Component
{
    public Employee $employee;

    public function placeholder()
    {
        return view('livewire.placeholder.profile');
    }

    public function render()
    {
        return view('livewire.hr-manager.employees.payroll-summary');
    }
}

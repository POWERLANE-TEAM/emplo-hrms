<?php

namespace App\Livewire\Employee\Payslip;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;

class UploadPayslip extends Component
{
    use WithFileUploads;

    public $file;

    #[Locked]
    public $employee;

    #[On('uploadPayslip')]
    public function getEmployee(int $employeeId)
    {
        $this->employee = Employee::findOrFail($employeeId);
    }

    public function render()
    {
        return view('livewire.employee.payslip.upload-payslip');
    }
}

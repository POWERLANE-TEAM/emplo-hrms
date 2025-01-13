<?php

namespace App\Livewire\Employee\Payslip;

use App\Enums\FilePath;
use App\Livewire\Employee\Tables\AnyEmployeePayslipsTable;
use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadPayslip extends Component
{
    use WithFileUploads;

    public $file;

    public bool $loading = true;

    #[Locked]
    public $employee;

    #[Locked]
    public $payroll;

    #[On('uploadPayslip')]

    public function getEmployee(array $eventPayload)
    {
        $this->employee = Employee::findOrFail($eventPayload['employee']);

        $this->payroll = $eventPayload['payroll'];

        $this->loading = false;
    }
    
    public function save()
    {
        $this->validate();

        Storage::disk('local')->makeDirectory(FilePath::PAYSLIPS->value);

        $hashedVersion = sprintf(
            '%s-%s', $this->file->hashName(), $this->employee->employee_id
        );

        $this->file->storeAs(FilePath::PAYSLIPS->value, $hashedVersion, 'local');

        DB::transaction(function () use ($hashedVersion) {
            $this->employee->payslips()->create([
                'uploaded_by' => $this->uploader->employee_id,
                'hashed_attachment' => $hashedVersion,
                'attachment_name' => $this->file->getClientOriginalName(),
                'payroll_id' => $this->payroll,
            ]);
        });

        $this->dispatch('payslipUploaded')->to(AnyEmployeePayslipsTable::class);
        $this->dispatch('payslipUploaded', [
            'type' => 'success',
            'message' => __("{$this->employee->last_name}'s payslip was uploaded successfully.")
        ]);

        $this->reset();
    }

    #[Computed]
    private function uploader()
    {
        return Auth::user()->account;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|max:10240|mimes:pdf',
        ];    
    }

    public function messages(): array
    {
        return [
            'file.required' => __('Payslip file cannot be empty.'),
            'file.file' => __('The uploaded file must be a valid file.'),
            'file.mimes' => __('The file must be a .pdf extension.')
        ];
    }

    public function render()
    {
        return view('livewire.employee.payslip.upload-payslip');
    }
}

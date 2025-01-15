<?php

namespace App\Livewire\HrManager\Employees;

use App\Enums\ContractType;
use App\Enums\FilePath;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class Contract extends Component
{
    use WithFileUploads;

    public Employee $employee;

    public $contract;

    public $addendum;

    #[Locked]
    public $routePrefix;

    public function updatedContract()
    {
        $this->authorize('uploadContractAttachment');

        $this->validateOnly('contract');

        Storage::disk('local')->makeDirectory(FilePath::CONTRACTS->value);

        $hashedVersion = sprintf(
            '%s-%s', $this->contract->hashName(), $this->employee->employee_id
        );

        $this->contract->storeAs(FilePath::CONTRACTS->value, $hashedVersion, 'local');

        DB::transaction(function () use ($hashedVersion) {
            $this->employee->contracts()->create([
                'type' => ContractType::CONTRACT,
                'uploaded_by' => Auth::user()->account->employee_id,
                'hashed_attachment' => $hashedVersion,
                'attachment_name' => $this->contract->getClientOriginalName(),
            ]);
        });

        $this->resetExcept('employee');

        $this->dispatch('contractUploaded', [
            'type' => 'success',
            'message' => __("{$this->employee->last_name}'s contract was uploaded successfully."),
        ]);

        $this->dispatch('refreshDatatable');
    }

    public function updatedAddendum()
    {
        $this->authorize('uploadContractAttachment');

        $this->validateOnly('addendum');

        Storage::disk('local')->makeDirectory(FilePath::CONTRACTS->value);

        $hashedVersion = sprintf(
            '%s-%s', $this->addendum->hashName(), $this->employee->employee_id
        );

        $this->addendum->storeAs(FilePath::CONTRACTS->value, $hashedVersion, 'local');

        DB::transaction(function () use ($hashedVersion) {
            $this->employee->contracts()->create([
                'type' => ContractType::ADDENDUM,
                'uploaded_by' => Auth::user()->account->employee_id,
                'hashed_attachment' => $hashedVersion,
                'attachment_name' => $this->addendum->getClientOriginalName(),
            ]);
        });

        $this->resetExcept('employee');

        $this->dispatch('addendumUploaded', [
            'type' => 'success',
            'message' => __("{$this->employee->last_name}'s contract addendum was uploaded successfully."),
        ]);

        $this->dispatch('refreshDatatable');
    }

    public function rules(): array
    {
        return [
            'contract' => 'file|max:10240|mimes:pdf',
            'addendum' => 'file|max:10240|mimes:pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'contract.file' => __('Contract file must be a valid file.'),
            'contract.max' => __('Contract file must not exceed 10 mb'),
            'contract.mimes' => __('Contract file must be a .pdf extension.'),
            'addendum.file' => __('Addendum file must be a valid file.'),
            'addendum.max' => __('Addendum file must not exceed 10 mb'),
            'addendum.mimes' => __('Addendum file must be a .pdf extension.'),
        ];
    }

    public function render()
    {
        return view('livewire.hr-manager.employees.contract');
    }
}

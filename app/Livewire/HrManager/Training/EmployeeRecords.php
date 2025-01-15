<?php

namespace App\Livewire\HrManager\Training;

use App\Models\TrainingProvider;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Training;
use App\Enums\TrainingStatus;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class EmployeeRecords extends Component
{
    public Employee $employee;

    #[Locked]
    public $routePrefix;

    public $title;

    public $provider;

    public int|string $trainer;

    public $trainerType;

    public $isTrainerOutsourced = false;

    public $completion;

    public $description;

    public $startDate;

    public $endDate;

    public $expiryDate;

    // this is atrocious. I had no choice left with no time.
    public function save()
    {
        // authorize
        
        $this->validate();

        DB::transaction(function () {
            $outsourcedTrainer = null;

            if ($this->isTrainerOutsourced) {
                [$firstName, $middleName, $lastName] = $this->splitFullName($this->trainer);
    
                $provider = TrainingProvider::create([
                    'training_provider_name' => $this->provider,
                ]);
    
                $outsourcedTrainer = $provider->outsourcedTrainers()->create([
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                ]);
            }

            Training::create([
                'trainee' => $this->employee->employee_id,
                'training_title' => $this->title,
                'trainer_type' => $this->isTrainerOutsourced ? 'outsourced_trainer' : 'employee',
                'trainer_id' => $this->isTrainerOutsourced ? $outsourcedTrainer->trainer_id : $this->trainer,
                'description' => $this->description,
                'completion_status' => $this->completion,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'expiry_date' => $this->expiryDate,
            ]);
        });

        $this->resetExcept('routePrefix', 'employee');

        $this->dispatch('trainingRecordCreated', [
            'type' => 'success',
            'message' => __("{$this->employee->last_name}'s new training record was created successfully.")
        ]);
        $this->dispatch('refreshDatatable');
    }

    private function splitFullName($fullName)
    {
        $fullName = trim($fullName);
        $nameParts = explode(' ', $fullName);

        $firstName = $nameParts[0] ?? null;
        $lastName = $nameParts[count($nameParts) - 1] ?? null;
        $middleName = null;

        if (count($nameParts) > 2) {
            $middleName = implode(' ', array_slice($nameParts, 1, -1));
        }

        return [$firstName, $middleName, $lastName];
    }


    public function rules()
    {
        return [
            'title'         => 'required|string|max:255',
            'provider'      => 'required_if:isTrainerOutsourced,true|max:255',
            'trainer'       => 'required|',
            'completion'    => 'required',
            'description'   => 'nullable|string|max:5000',
            'startDate'     => 'required|date',
            'endDate'       => 'nullable|date|after_or_equal:startDate',
            'expiryDate'    => 'nullable|date|after_or_equal:endDate',
        ];
    }

    #[Computed]
    public function completionStatuses()
    {
        return TrainingStatus::options();
    }

    #[Computed]
    public function employeeOptions()
    {
        return Employee::all()
            ->mapWithKeys(fn ($item) => [$item->employee_id => $item->full_name])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.hr-manager.training.employee-records');
    }
}

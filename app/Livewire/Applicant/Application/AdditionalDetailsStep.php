<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\CivilStatus;
use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use App\Traits\Applicant;
use App\Traits\HasAddressForm;
use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Spatie\LivewireWizard\Components\StepComponent;

class AdditionalDetailsStep  extends StepComponent
{
    use HasAddressForm;

    // used to tag the step is complete
    // has no actual use yet
    public bool $isValid = false;

    public string $civilStatus =  CivilStatus::SINGLE->value;

    public function mount()
    {
        $this->presentAddressFields['regions'] = $this->regions;
        $this->permanentAddressFields['regions'] = $this->regions;
        $this->presentAddressFields['provinces'] = $this->provinces;

        $this->address['presentRegion'] = $this->initialAddressState['region'];
    }

    public function validateNow()
    {

        $this->isValid = false;

        // $this->validate();

        $this->isValid = true;

        $this->nextStep();
    }

    public function updating($property, $value)
    {
        $this->updatingAddress($property, $value = null);
    }


    public function updated($property, $value)
    {
        $this->updatedAddress($property);
    }

    public function stepInfo(): array
    {
        return [
            'title' => 'Additional Details',
            // tags if complete and fields were valid
            'isComplete' => $this->isValid,
        ];
    }

    public function dispatchSameAddress()
    {
        $this->dispatch('same-as-present-address');
    }

    /**
     * Accessor for civil statuses, returning key / value pairs of enum cases and labels.
     *
     * @return array
     */
    #[Computed]
    public function civilStatuses()
    {
        return CivilStatus::options();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('livewire.applicant.application.additional-details-step');
    }
}

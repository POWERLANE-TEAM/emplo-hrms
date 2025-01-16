<?php

namespace App\Livewire\Employee\Applicants;

use App\Models\InterviewRating;
use App\Http\Controllers\InitialInterviewController;
use App\Livewire\Forms\Applicant\InterviewForm;
use App\Models\InitialInterview;
use App\Models\InterviewParameter;
use App\Rules\ValidApplicantInterviewRating;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SetInitInterviewResult extends Component
{

    public InitialInterview $initInterview;

    public InterviewForm $initForm;

    public $interviewParameterItems;

    public function mount()
    {
        $this->initForm->interviewRatings = $this->initForm->initializeInterviewRatings();
    }


    public function rules()
    {
        return [
            'initForm.interviewRatings' => 'required',
            'initForm.interviewRatings.*' => 'bail|required|' . ValidApplicantInterviewRating::getRule(),
        ];
    }

    public function save(InitialInterviewController $controller)
    {

        $validated = $this->initForm->validate();

        [$averageRating, $countPassed] = $this->initForm->averageRating();

        // assume ko nlng ganto
        // passed if average rating is 2.0 or higher and at least 5 ratings are passed
        $isPassed = $this->initForm->ratingIsPassing($averageRating) && $countPassed >= 5;

        $validated['applicationId'] = $this->initInterview->application->application_id;
        $validated['isPassed'] = $isPassed;

        $controller->update($validated, true);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Initial inteview result has been updated',
        ]);

        $this->dispatch('refreshChanges');
    }

    public function render()
    {
        dump($this->getErrorBag());
        dump($this->rules());
        dump($this->initForm->interviewRatings);
        return view('livewire.employee.applicants.set-init-interview-result');
    }
}

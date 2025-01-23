<?php

namespace App\Livewire\Employee\Applicants;

use App\Models\InterviewRating;
use App\Http\Controllers\InitialInterviewController;
use App\Livewire\Forms\Applicant\InterviewForm;
use App\Models\FinalInterviewRating;
use App\Models\InitialInterview;
use App\Models\InitialInterviewRating;
use App\Models\InterviewParameter;
use App\Rules\ValidApplicantInterviewRating;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SetInitInterviewResult extends Component
{

    public InitialInterview $initInterview;

    public InterviewForm $initForm;

    #[Locked]
    public $interviewParameterItems;

    public function mount()
    {
        $existingRatings = $this->initForm->getExistingInterviewRatings(new InitialInterviewRating, $this->initInterview);

        if ($existingRatings) {
            $this->initForm->interviewRatings = $existingRatings;
        } else {
            $this->initForm->interviewRatings = $this->initForm->initializeInterviewRatings();
        }
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
        return view('livewire.employee.applicants.set-init-interview-result');
    }
}

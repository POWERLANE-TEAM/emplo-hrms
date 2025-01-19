<?php

namespace App\Livewire\Employee\Applicants;

use App\Models\InterviewRating;
use App\Http\Controllers\FinalInterviewController;
use App\Livewire\Forms\Applicant\InterviewForm;
use App\Models\FinalInterviewRating;
use App\Models\FinalInterview;
use App\Models\InterviewParameter;
use App\Rules\ValidApplicantInterviewRating;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SetFinalInterviewResult extends Component
{

    public FinalInterview $finalInterview;

    public InterviewForm $finalForm;

    public $interviewParameterItems;

    public function mount()
    {
        $existingRatings = $this->finalForm->getExistingInterviewRatings(new FinalInterviewRating, $this->finalInterview);

        if ($existingRatings) {
            $this->finalForm->interviewRatings = $existingRatings;
        } else {
            $this->finalForm->interviewRatings = $this->finalForm->initializeInterviewRatings();
        }
    }

    public function rules()
    {
        return [
            'finalForm.interviewRatings' => 'required',
            'finalForm.interviewRatings.*' => 'bail|required|' . ValidApplicantInterviewRating::getRule(),
        ];
    }

    #[On('submit-final-interview-result')]
    public function save(FinalInterviewController $controller)
    {

        $validated = $this->finalForm->validate();

        [$averageRating, $countPassed] = $this->finalForm->averageRating();

        // assume ko nlng ganto
        // passed if average rating is 2.0 or higher and at least 5 ratings are passed
        $isPassed = $this->finalForm->ratingIsPassing($averageRating) && $countPassed >= 5;

        $validated['applicationId'] = $this->finalInterview->application->application_id;
        $validated['isPassed'] = $isPassed;

        $controller->update($validated, true);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Final inteview result has been updated',
        ]);

        $this->dispatch('refreshChanges');
    }

    public function render()
    {
        return view('livewire.employee.applicants.set-final-interview-result');
    }
}

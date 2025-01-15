<?php

namespace App\Livewire\Employee\Applicants;

use App\Models\InterviewRating;
use App\Http\Controllers\InitialInterviewController;
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

    public $interviewParameters;

    #[Locked]
    public $initInterviewParameters = [];

    #[Validate]
    public $initInterviewRatings = [];

    public function mount()
    {
        $this->initInterviewRatings = InterviewParameter::all()->pluck('parameter_id')->mapWithKeys(function ($id) {
            return [$id => ''];
        })->toArray();
    }

    #[Computed(persist: true, cache: true, key: 'interviewRatingOptions')]
    public function interviewRatingOptions()
    {
        return InterviewRating::getRatingValues();
    }

    public function interviewRatingOptionsF($reverse = false)
    {
        $options =InterviewRating::getRatings();
        return $reverse ? array_reverse($options, true) : $options;
    }


    public function rules()
    {
        return [
            'initInterviewRatings' => 'required',
            'initInterviewRatings.*' => 'bail|required|' . ValidApplicantInterviewRating::getRule(),
        ];
    }

    public function ratingIsPassing($value)
    {
        // assume ko nlng ganto
        return round($value) >= 2.0;
    }

    public function averageRating()
    {
        $total = 0;
        $count = 0;
        $ratingPassedCount = 0;
        $values = InterviewRating::getRatingValues();

        foreach ($this->initInterviewRatings as $rating) {
            if ($rating) {
                $ratingVal = $values[$rating];
                if ($this->ratingIsPassing($ratingVal)) $ratingPassedCount++;
                $total += $ratingVal;
                $count++;
            }
        }
        dump($this->initInterviewRatings);
        dump($values);
        dump($total);
        $average = $count ? rtrim(rtrim(number_format($total / $count, 10), '0'), '.') : 0;
        return  [$average, $ratingPassedCount];
    }

    public function save(InitialInterviewController $controller)
    {

        $validated = $this->validate();

        [$averageRating, $countPassed] = $this->averageRating();

        // assume ko nlng ganto
        // passed if average rating is 2.0 or higher and at least 5 ratings are passed
        $isPassed = $this->ratingIsPassing($averageRating) && $countPassed >= 5;

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
        dump($this->initInterviewRatings);
        return view('livewire.employee.applicants.set-init-interview-result');
    }
}

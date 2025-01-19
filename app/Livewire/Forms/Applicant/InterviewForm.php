<?php

namespace App\Livewire\Forms\Applicant;

use App\Models\FinalInterviewRating;
use App\Models\InitialInterviewRating;
use App\Models\InterviewParameter;
use App\Models\InterviewRating;
use App\Rules\ValidApplicantInterviewRating;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InterviewForm extends Form
{


    #[Locked]
    public $interviewParameters = [];

    #[Validate]
    public $interviewRatings = [];


    #[Computed]
    public function initializeInterviewRatings()
    {
        return InterviewParameter::all()->pluck('parameter_id')->mapWithKeys(function ($id) {
            return [$id => ''];
        })->toArray();
    }

    public function getExistingInterviewRatings(InitialInterviewRating|FinalInterviewRating $ratingModel, $interview)
    {
        $ratings = $ratingModel::where('interview_id', $interview->interview_id)->get();

        return $ratings->mapWithKeys(function ($rating) {
            return [$rating->parameter_id => $rating->rating_id];
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
            'interviewRatings' => 'required',
            'interviewRatings.*' => 'bail|required|' . ValidApplicantInterviewRating::getRule(),
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

        foreach ($this->interviewRatings as $rating) {
            if ($rating) {
                $ratingVal = $values[$rating];
                if ($this->ratingIsPassing($ratingVal)) $ratingPassedCount++;
                $total += $ratingVal;
                $count++;
            }
        }

        $average = $count ? rtrim(rtrim(number_format($total / $count, 10), '0'), '.') : 0;
        return  [$average, $ratingPassedCount];
    }
}

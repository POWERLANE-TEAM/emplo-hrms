<?php

namespace App\Livewire\Forms;

use App\Rules\ScheduleDateRule;
use App\Rules\ScheduleTimeRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ScheduleForm extends Form
{

    public $minDate;
    public $maxDate;

    public $minTime;
    public $maxTime;

    public $date = '';

    public $time = '';

    protected function rules()
    {
        return [
            'date' => (function () {

                return 'bail|required|' .  ScheduleDateRule::get($this->minDate, $this->maxDate);
            })(),

            'time' => (function () {
                return [
                    'bail',
                    'required_with:date',
                    new ScheduleTimeRule($this->date)
                ];
            })(),

        ];
    }

    public function setMinDate($minDate = null)
    {
        $this->minDate = $minDate;
    }

    public function setMaxDate($maxDate = null)
    {
        $this->maxDate = $maxDate;
    }

    public function setMinTime($minTime = null)
    {
        $this->minTime = $minTime;
    }

    public function setMaxTime($maxTime = null)
    {
        $this->maxTime = $maxTime;
    }
}
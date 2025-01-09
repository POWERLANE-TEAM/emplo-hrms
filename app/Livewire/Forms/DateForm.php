<?php

namespace App\Livewire\Forms;

use App\Rules\ScheduleDateRule;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DateForm extends Form
{
    public $minDate;

    public $maxDate;

    public $date = '';

    protected function rules()
    {
        return [
            'date' => (function () {
                return 'required|' . ScheduleDateRule::get($this->minDate, $this->maxDate);
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


    public function getMinDate()
    {
        return $this->minDate;
    }

    public function getMaxDate()
    {
        return $this->maxDate;
    }
}

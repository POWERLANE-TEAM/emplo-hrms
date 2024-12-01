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
                return 'bail|required|' . ScheduleDateRule::get($this->minDate, $this->maxDate);
            })(),
        ];
    }

    public function setMinDate($minDate = null)
    {
        $this->minDate = $minDate;
        Log::info('Setting min date to ' . $minDate);
    }

    public function setMaxDate($maxDate = null)
    {
        $this->maxDate = $maxDate;
        Log::info('Setting max date to ' . $maxDate);
    }
}

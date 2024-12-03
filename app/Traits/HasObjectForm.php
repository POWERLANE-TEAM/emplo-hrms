<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Livewire\Mechanisms\ComponentRegistry;

trait HasObjectForm
{
    public function mountWithObjectForm(string $formClass = null, string $formProperty = 'form')
    {
        if (!$formClass) {
            return;
        }
        $states = $this->state()->currentStep();

        $this->$formProperty = new $formClass($this, $formProperty);

        // Log::info('states', $states);

        $currentComponentName = app(ComponentRegistry::class)->getName(static::class);

        if (isset($states[$currentComponentName])) {
            $this->$formProperty->fill($states[$currentComponentName][$formProperty]);
        }
    }
}

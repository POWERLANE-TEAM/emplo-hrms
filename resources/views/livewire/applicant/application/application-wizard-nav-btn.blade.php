@php

    if ($this->hasNextStep()) {
        $nextStepText = 'Next';
        $nextStepClass = 'col-8';
    } else {
        $nextStepText = 'Submit';
        $nextStepClass = '';
    }

@endphp
<section class="container ">
    <div class="d-flex justify-content-between {{ $nextStepClass }} mx-auto">
        @if ($this->hasPreviousStep())
            <div wire:click="previousStep" class="btn btn-outline-primary ">
                Previous
            </div>
        @else
            <div></div>
        @endif

        @if ($this->hasNextStep())
            {{-- <div wire:click="nextStep" class="btn btn-primary "> --}}
            <div wire:click="validateNow" class="btn btn-primary submit-link">
            @else
                <div wire:click="save" class="btn btn-primary submit-link">
        @endif

        {{ $nextStepText }}
    </div>
    </div>
</section>

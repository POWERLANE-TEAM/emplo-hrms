@php
    $allStates = $this->state()->all();
    $currentState = $this->state()->currentStep();
    $stepsInfo = $currentState['steps'];

    // dump($allStates);
    // dump($currentState);

@endphp

<div class="my-3 my-lg-5">
    <div class="d-flex justify-content-center">
        <div>
            @foreach ($stepsInfo as $thisStep)
                <span class="{{ $thisStep->isCurrent() ? ' fw-bold' : '' }}  ">
                    {{ $thisStep->info['title'] }}</span>
            @endforeach
        </div>
    </div>
</div>

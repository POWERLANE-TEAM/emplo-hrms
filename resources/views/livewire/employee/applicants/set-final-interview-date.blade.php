@use ('Illuminate\View\ComponentAttributeBag')

<form wire:submit.prevent="store" id="final-interview-sched-form"
    action="{{ route(($routePrefix ?: 'employee') . '.applicant.final-inteview.store', ['application' => $application->application_id]) }}"
    method="POST" nonce="{{ $nonce }}">
    @isset($postMethod)
        @method($postMethod)
    @endisset

    <div id="final-interview-group">Final Interview</div>
    <div {{new ComponentAttributeBag($inputGroupAttributes)}}  aria-labelledby="final-interview-group">
        <div {{new ComponentAttributeBag($dateWrapAttributes)}}>
            <x-form.boxed-date label="Date" id="final-interview-date"
                class=" {{ $errors->has('interview.date') ? 'is-invalid' : '' }}" name="interview.date"
                placeholder="mm/dd/yyy" :nonce="$nonce">

                <x-slot:feedback>
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'final-interview-date',
                        'message' => $errors->first('interview.date'),
                    ])
                </x-slot:feedback>
            </x-form.boxed-date>
        </div>

        <div {{new ComponentAttributeBag($timeWrapAttributes)}}>
            <x-form.boxed-date type="time" label="Time" id="final-interview-time"
                class=" {{ $errors->has('interview.time') ? 'is-invalid' : '' }}" name="interview.time"
                placeholder="mm/dd/yyy" :nonce="$nonce">

                <x-slot:feedback>
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'final-interview-date',
                        'message' => $errors->first('interview.time'),
                    ])
                </x-slot:feedback>
            </x-form.boxed-date>
        </div>
    </div>

</form>


<script>
    // document.addEventListener('livewire:init', () => {
    //     Livewire.on('submit-interview-sched-form', (event) => {
    //         @this.call('store');
    //     });
    // });
</script>

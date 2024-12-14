<form wire:submit.prevent="store" id="interview-sched-form"
    action="{{ route(($routePrefix ?: 'employee') . '.applicant.initial-inteview.store', ['application' => $applicationId]) }}"
    method="POST" nonce="{{ $nonce }}">
    @isset($postMethod)
        @method($postMethod)
    @endisset

    <div id="interview-group">Interview</div>
    <div class="input-group flex-md-nowrap gap-1 min-w-100" aria-labelledby="interview-group">
        <div class="col-12 col-md-6">
            <x-form.boxed-date label="Date" id="interview-date"
                class=" {{ $errors->has('interview.date') ? 'is-invalid' : '' }}" name="interview.date"
                placeholder="mm/dd/yyy" :nonce="$nonce">

                <x-slot:feedback>
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'interview-date',
                        'message' => $errors->first('interview.date'),
                    ])
                </x-slot:feedback>
            </x-form.boxed-date>
        </div>

        <div class="col-12 col-md-6">
            <x-form.boxed-date type="time" label="Time" id="interview-time"
                class=" {{ $errors->has('interview.time') ? 'is-invalid' : '' }}" name="interview.time"
                placeholder="mm/dd/yyy" :nonce="$nonce">

                <x-slot:feedback>
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'interview-date',
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

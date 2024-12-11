{{-- I get Route [.applicant.store] not defined. after a post request --}}
<form wire:submit.prevent="store" id="examination-sched-form"
    action="{{ route(($routePrefix ?: 'employee') . '.applicant.exam.store', ['application' => $applicationId]) }}"
    method="POST" nonce="{{ $nonce }}">

    @isset($postMethod)
        @method($postMethod)
    @endisset

    <input type="hidden" name="application_id" value="{{ $applicationId }}" disabled readonly autocomplete="off">

    <div id="examination-group">Examination</div>
    <div class="input-group flex-md-nowrap gap-1 min-w-100" aria-labelledby="examination-group">
        <div class="col-12 col-md-6">
            <x-form.boxed-date label="Date" id="examination-date"
                class=" {{ $errors->has('examination.date') ? 'is-invalid' : '' }}" name="examination.date"
                placeholder="mm/dd/yyy" :nonce="$nonce">

                <x-slot:feedback>
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'examination-date',
                        'message' => $errors->first('examination.date'),
                    ])
                </x-slot:feedback>
            </x-form.boxed-date>
        </div>

        <div class="col-12 col-md-6">
            <x-form.boxed-date type="time" label="Time" id="examination-time"
                class=" {{ $errors->has('examination.time') ? 'is-invalid' : '' }}" name="examination.time"
                placeholder="mm/dd/yyy" :nonce="$nonce">

                <x-slot:feedback>
                    @include('components.form.input-feedback', [
                        'feedback_id' => 'examination-date',
                        'message' => $errors->first('examination.time'),
                    ])
                </x-slot:feedback>
            </x-form.boxed-date>
        </div>
    </div>

</form>

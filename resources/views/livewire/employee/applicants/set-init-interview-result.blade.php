@use('Illuminate\View\ComponentAttributeBag')

<form action="" wire:submit.prevet="save" class="d-contents">
    @foreach ($interviewParameterItems as $parameter)
        <div class="row flex-nowrap align-items-center min-w-100  justify-content-between">
            <label for="exam-result " class="col-7">
                <div class="text-wrap ">
                    {{ $parameter->parameter_desc }}
                </div>

            </label>

            <input type="hidden" name="initForm.interviewParameters" value="{{ $parameter->parameter_id }}">
            <x-form.boxed-dropdown id="exam-result"
            :overrideContainerClass="true"
            :containerAttributes="new ComponentAttributeBag(['class' => 'mb-3 position-relative col-3 '])"
            class="{{$errors->has('initForm.interviewRatings.' . $parameter->parameter_id) ? 'is-invalid' : ''}}"
            name="initForm.interviewRatings.{{ $parameter->parameter_id }}"
                :nonce="$nonce"  :options="$this->initForm->interviewRatingOptionsF(true)" placeholder="Select type">
            </x-form.boxed-dropdown>
        </div>
    @endforeach
    <div class="row justify-content-center">
        <button id="submit-init-interview-result" wire:loading.attr="disabled" id=""
            class="btn btn-primary ndsbl">{{ __('Submit') }}</button>
    </div>
</form>

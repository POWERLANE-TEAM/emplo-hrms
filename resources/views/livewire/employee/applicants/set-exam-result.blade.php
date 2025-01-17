@use('Illuminate\View\ComponentAttributeBag')

<form wire:submit.prevent="save" class="col-8 mx-auto">
    <div class="row">
        <div class=" mb-3 mx-auto">

            <x-form.boxed-dropdown id="exam-result"  name="examResult"
            :nonce="$nonce" :required="true" :options="$this->basicEvalStatus" placeholder="Select type">
            <x-slot:feedback>
                @include('components.form.input-feedback', [
                    'feedback_id' => 'exam-result-feedback',
                    'message' => $errors->first('examResult'),
                ])
            </x-slot:feedback>
        </x-form.boxed-dropdown>
        </div>
    </div>
    {{-- INSERT  notice --}}

    <div class="row justify-content-center">
        <button id="submit-exam-result" wire:loading.attr="disabled" class="btn btn-primary ndsbl">{{ __('Submit') }}</button>
    </div>
</form>

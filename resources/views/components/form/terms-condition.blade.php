<div class="input-group mb-3 terms-condition">
    <input type="checkbox" id="terms-condition" name="consent" required wire:model="consent"
        {{ $attributes->merge(['class' => 'checkbox checkbox-primary']) }} aria-owns="terms-condition-feedback">
    <label for="terms-condition" class="checkbox-label d-flex flex-wrap">{!! trans('consent.consent') !!}&#8194;<wbr>
        <span class="d-flex" role="list">
            <a href="#" target="_blank" class="text-black" rel="noopener noreferrer"
                role="listitem">{!! __('consent.term_condition') !!}
            </a>
            <span>&#8194;{{ __('common.and') }}&#8194;</span>
            <a href="#" target="_blank" class="text-black" rel="noopener noreferrer">{!! __('consent.privacy_policy') !!}</a>
        </span>
    </label>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>

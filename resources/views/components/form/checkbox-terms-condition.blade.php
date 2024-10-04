<x-form.checkbox
    {{ $attributes->merge([
        'id' => $attributes->get('id', 'terms-condition'),
        'name' => $attributes->get('name', 'consent'),
        'class' => $errors->has($attributes->get('id', 'terms-condition')) ? 'is-invalid' : '',
    ]) }}
    container_class="terms-condition mb-3" :nonce="$nonce">

    <x-slot:label>
        {!! trans('consent.consent') !!}&#8194;<wbr>
        <span class="d-flex" role="list">
            <a href="#" target="_blank" class="text-black" rel="noopener noreferrer"
                role="listitem">{!! __('consent.term_condition') !!}
            </a>
            <span>&#8194;{{ __('common.and') }}&#8194;</span>
            <a href="#" target="_blank" class="text-black" rel="noopener noreferrer">{!! __('consent.privacy_policy') !!}</a>
        </span>
    </x-slot:label>

    <x-slot:feedback>
        @include('components.form.input-feedback', [
            'feedback_id' => 'terms-condition-feedback',
            'message' => $errors->first('consent'),
        ])
    </x-slot:feedback>

</x-form.checkbox>

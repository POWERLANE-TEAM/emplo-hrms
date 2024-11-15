{{--
* |--------------------------------------------------------------------------
* | Snippet Intro Heading for Forms
* |--------------------------------------------------------------------------
--}}

@props(['label', 'nonce', 'required' => false, 'description' => ''])

<label class="mb-1 fw-semibold">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>

<p>{{ $description ?? '' }} {{ $slot }}</p>
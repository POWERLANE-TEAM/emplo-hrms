{{--
* |--------------------------------------------------------------------------
* | Boxed: Text Area with Attachments on the Right
* |--------------------------------------------------------------------------
--}}

@props(['label', 'nonce', 'required' => false, 'description' => null, 'readonly' => false])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold text-secondary-emphasis">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if ($required)
        <span class="text-danger">*</span>
    @endif
</label>

{{-- Optional description below the label --}}
@if ($description)
    <p class="fs-7 mb-3">{!! $description !!}</p>
@endif

<div class="input-group mb-3 position-relative" id="custom-textarea-container">
    {{-- Editable Content Area Styled Like a Textarea --}}
    <div id="{{ $attributes->get('id') }}"
        class="form-control border ps-3 rounded text-start"
        style="overflow-y: auto; padding-bottom: 40px;" nonce="{{ $nonce }}"
        {{ $readonly ? 'disabled' : '' }} aria-owns="{{ $attributes->get('id') }}-feedback"></div>

    {{-- Attachments Section --}}
    <div id="attachments-container" class="attachments-wrapper rounded-bottom">
        {{-- Attachments List --}}
        <div class="attachments-list flex-grow-1" id="attachments-list">
            {{ $preview }}
        </div>

        @if (! $readonly)
            <label
                class="btn no-hover-border btn-sm hover-opacity d-inline-flex align-items-center justify-content-center attach-files-button">
                <i data-lucide="paperclip" class="icon text-primary icon-large"></i>
                <input type="file" @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif class="d-none" multiple">
            </label>
        @endif
    </div>
</div>
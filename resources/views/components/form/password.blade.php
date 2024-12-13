@props(['label', 'nonce', 'input_icon_left', 'has_confirm' => false])

@isset($label)
    <label for="{{ $attributes->get('id') }}" class="mb-1">{{ $label }}</label>
@endisset
<div class="input-group mb-3">
    <div class="px-2 d-flex position-absolute icon  text-primary" wire:ignore nonce="{{ $nonce }}"><i
            data-lucide="lock-keyhole" class="icon-large"></i>
        @if (!empty($input_icon_left))
            {{ $input_icon_left }}
        @endif
    </div>
    <input type="password"
        {{ $attributes->merge([
                'class' => 'form-control rm-bg-icon border-bottom ps-5 z-0',
                'autocomplete' => $attributes->get('autocomplete', 'off'),
                'pattern' =>
                    $attributes->has('pattern') && !empty($attributes->get('pattern'))
                        ? $attributes->get('pattern')
                        : '^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[^\s]{8,72}$',
                'passwordrules' =>
                    'minlength: 8; maxlength: 72; required: lower; required: upper; required: digit; required: special; allowed: [-().&@?\'#,/&quot;+]; max-consecutive: 2',
            ])->except(['pattern']) }}
        @if ($attributes->has('pattern')) @if (!empty($attributes->get('pattern')))
            pattern="{{ $attributes->get('pattern') }}" @endif
    @else pattern="^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[^\s]{8,72}$" @endif
    aria-owns="{{ $attributes->get('id') }}-feedback {{ $has_confirm ? $attributes->get('id') . '-confirm' : '' }}"
    minlength="8" maxlength="72" required
    @if ($attributes->has('name')) wire:model="{{ $attributes->get('name') }}" @endif nonce="{{ $nonce }}">

    @if (!empty($toggle_password))
        {{ $toggle_password }}
    @endif
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>

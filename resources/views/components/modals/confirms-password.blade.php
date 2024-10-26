@props(['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    {{ $slot }}
</span>

@once
    <div class="modal {{ $this->confirmingPassword ? 'd-block' : 'fade' }}" tabindex="-1" aria-labelledby="confirmsPasswordLabel" aria-hidden="{{ ! $this->confirmingPassword }}">
        <div class="modal-dialog modal-lg" wire:model.live="confirmingPassword">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmsPasswordLabel">{{ $title }}</h1>
                </div>
                <div class="modal-body">
                    <div class="mb-3" x-data="{}" x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
                        <div for="content" class="col-form-label">{{ $content }}</div>
                        <x-form.input-text type="password" label="{{ __('Password:') }}" autocomplete="current-password" name="confirmablePassword" id="confirmable_password" x-ref="confirmable_password" class="form-control" />
                        @includeWhen($errors->first('confirmable_password'), 'components.form.input-feedback', [
                            'feedback_id' => 'confirmable_password', 
                            'message' => $errors->first('confirmable_password')
                        ])                    
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="stopConfirmingPassword" wire:loading.attr="disabled" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button wire:click="confirmPassword" wire:loading.attr="disabled" class="btn btn-primary">{{ $button }}</button>
                </div>
            </div>
        </div>
    </div>
@endonce

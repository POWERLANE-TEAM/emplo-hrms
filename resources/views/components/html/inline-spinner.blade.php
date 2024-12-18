@props(['spinnerColor' => 'primary', 'text' => '', 'target' => ''])
@php
    $targets = is_array($target) ? implode(',', $target) : $target;
@endphp
<span class="spinner-border spinner-border-sm text-{{ $spinnerColor }}" aria-hidden="true" wire:loading
    {{ $targets ? 'wire:target=' . $targets : '' }} nonce="{{ $nonce }}"></span>

@if ($text)
    <span class="visually-hidden " role="status" wire:loading {{ $targets ? 'wire:target=' . $targets : '' }}
        nonce="{{ $nonce }}">{{ $text }}</span>
@endif

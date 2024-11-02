@aware(['nonce'])

<input type="checkbox" id="{{ $toggler_id }}" class="text-primary toggle-password position-absolute end-0 z-3"
    name="toggle_password" aria-label="{{ $label ?? 'Show/Hide Password' }}" aria-keyshortcuts="alt+f8"
    aria-controls="{{ $controls }}" nonce="{{ $nonce }}">

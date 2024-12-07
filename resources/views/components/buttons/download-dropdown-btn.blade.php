{{--
* |--------------------------------------------------------------------------
* | Main Button: Submit, Cancel
* |
* | Note: The Enable/Disable of the button is passable.
* |--------------------------------------------------------------------------
--}}

@props([])


<div class="btn-group">
    <x-buttons.download-btn class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Download as</x->

        <ul class="dropdown-menu">
            {{ $slot }}
        </ul>
</div>

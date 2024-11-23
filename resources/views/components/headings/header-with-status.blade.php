{{--
* |--------------------------------------------------------------------------
* | Header with Status Badge
* |--------------------------------------------------------------------------
--}}


<hgroup class="my-2">
    <div class="fs-2 fw-bold">
        {{ $title }}
        <span class="fs-3 ms-2">
            <x-status-badge :color="$color">
                {{ $badge }}
            </x-status-badge>
        </span>
    </div>

    <div>
        {{ $slot }}
    </div>
</hgroup>
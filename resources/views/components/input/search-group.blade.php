@props([
    'container_class' => 'col-8 col-md-4',
])

<div class="d-flex justify-content-center align-items-center w-100">
    <div class="{{ $container_class ?? '' }}">
        <div class="input-group ">
            @if (!empty($left_icon))
                <span class="input-group-text icon overlap left">
                    {{ $left_icon }}
                </span>
            @endif

            {{ $slot }}

            @if (!empty($right_icon))
                <span class="input-group-text icon overlap right">
                    {{ $right_icon }}
                </span>
            @endif
        </div>
    </div>
</div>

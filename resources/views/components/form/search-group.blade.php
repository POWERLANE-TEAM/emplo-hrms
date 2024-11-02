@props([
    'nonce' => '',
    'parent_class' => '',
    'container_class' => '',
])

<div {{ $attributes->merge(['class' => 'd-flex align-items-center']) }}>
    <search class="{{ $container_class ?? '' }}">
        <div class="input-group ">
            @if (!empty($left_icon))
                <span class="input-group-text icon overlap left">
                    {{ $left_icon }}
                </span>
            @endif

            {{ $slot }}

            <!-- @if (!empty($right_icon))
                <span class="input-group-text icon overlap right">
                    {{ $right_icon }}
                </span>
            @endif -->
        </div>
    </search>
</div>

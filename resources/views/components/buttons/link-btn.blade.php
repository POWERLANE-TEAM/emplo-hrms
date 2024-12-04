{{-- 
* |-------------------------------------------------------------------------- 
* | Link Buttons 
* |-------------------------------------------------------------------------- 
--}}

@props(['label', 'nonce'])

<div class="bottom-0 pt-1 bg-body z-3 w-100">
    <a href="{{ $attributes->get('href') }}" 
       nonce="{{ $nonce }}" 
       {{ $attributes->merge(['class' => 'btn btn-lg']) }}>
        
        {{ $label }}
        <i data-lucide="chevron-right-circle" class="icon icon-large ms-1"></i>
    </a>
</div>

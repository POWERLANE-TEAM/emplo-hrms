{{-- 
* |-------------------------------------------------------------------------- 
* | Section Title
* |-------------------------------------------------------------------------- 
--}}

@props(['title', 'isNextSection' => false])

<p class="text-uppercase fs-5 fw-semibold text-primary {{ $isNextSection ? 'pt-4' : '' }}">
    {{ $title }}
</p>


@extends('components.layout.app', ['description' => 'Hiring Page', 'font_weights' => ['900', '600']])

@section('head')
<title>Powerlane</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/0.159.0/three.min.js"></script>

    <script>
        if (window.location.hash === '#_=_') {
            history.replaceState ?
                history.replaceState(null, null, window.location.href.split('#')[0]) :
                window.location.hash = '';
        }
    </script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/landing.js'])
    @vite(['resources/js/animations/scroll-effect.js'])
@endPushOnce
@pushOnce('styles')
    @vite(['resources/css/landing.css'])

@endPushOnce


@section('critical-styles')
<style nonce="{{ $nonce }}">
    {!! Vite::content('resources/css/guest/primary-bg.css') !!}
</style>
@endsection

@section('before-nav')
    <section class="top-vector">

        <svg class="left-circle" width="171" height="251" viewBox="0 0 171 251" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M156 125.5C156 186.527 106.527 236 45.5 236C-15.5275 236 -65 186.527 -65 125.5C-65 64.4725 -15.5275 15 45.5 15C106.527 15 156 64.4725 156 125.5Z"
                stroke="#404040" stroke-opacity="0.05" stroke-width="30" />
        </svg>

        <svg class="right-circle " width="145" height="145" viewBox="0 0 145 145" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M137.5 72.5C137.5 108.399 108.399 137.5 72.5 137.5C36.6015 137.5 7.5 108.399 7.5 72.5C7.5 36.6015 36.6015 7.5 72.5 7.5C108.399 7.5 137.5 36.6015 137.5 72.5Z"
                stroke="#61B000" stroke-width="15" />
        </svg>

    </section>
@endsection

@section('header-nav')
<x-layout.guest.landing-header />
@endsection

@section('content')
<div class="content">
    <div class="hero hidden-until-load fadein-text">
        <div class="hero__visual">
            <!-- Three.js canvas -->
        </div>
        <div class="hero__content">

            <header class="typing-loop display-5 fw-bold text-primary mb-3 d-none d-md-block">
                {{ __('Welcome to EMPLO!') }}
            </header>

            <div class="fadein-text">
                <p>Powerlane Resources Inc. is a manpower company that is stationed in Santa Rosa, Laguna. We provide
                    reliable workforce solutions, connecting businesses with skilled professionals to drive success and
                    growth.</p>

                <div class="d-flex align-items-center justify-content-start">
                    <a href="/hiring" class="btn btn-primary w-50">
                        Apply Now
                        <i data-lucide="rocket" class="icon icon-large ms-2"></i>
                    </a>

                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <section class="section-below">
    
</section> -->
@endsection
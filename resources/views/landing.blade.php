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

@section('header-nav')
<x-layout.guest.landing-header />
@endsection

@section('content')
<div class="content">
    <div class="hero">
        <div class="hero__visual">
            <!-- Three.js canvas -->
        </div>
        <div class="hero__content hidden-until-load">

            <header class="typing-loop display-5 fw-bold text-primary mb-3 d-none d-md-block">
                {{ __('Welcome to EMPLO!') }}
            </header>

            <div class="fadein-text">
                <p>Powerlane Resources Inc. is a manpower company that is stationed in Santa Rosa, Laguna. We provide
                    reliable workforce solutions, connecting businesses with skilled professionals to drive success and
                    growth.</p>

                <div class="d-flex align-items-center justify-content-start">
                    <a href="#demo" class="btn btn-primary w-50">
                        Apply Now
                        <i data-lucide="rocket" class="icon icon-large ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-below">
    <!-- Your next section content here -->
    <h2>Next Section</h2>
    <p>This section won't have the animation following it.</p>
</section>
@endsection
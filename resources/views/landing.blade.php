@extends('components.layout.app', ['description' => 'Hiring Page', 'font_weights' => ['900', '600']])

@section('head')
    <title>Powerlane | Job Listings</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
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
            <h1 class="fw-bold text-primary">Powerlane Resources, Inc.</h1>
            <p>Lorem ipsum lorem ipsum lorem ipsum. Lorem ipsum lorem ipsum lorem ipsum. Lorem ipsum lorem ipsum lorem ipsum. Lorem ipsum lorem ipsum lorem ipsum. Lorem ipsum lorem ipsum lorem ipsum.</p>
            <!-- <a href="#demo" class="cta">Get Started</a> -->
        </div>
    </div>
@endsection

@section('footer')
    <x-guest.footer />
@endsection

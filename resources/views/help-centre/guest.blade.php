@extends('components.layout.centre.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
<title>Information Centre</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/applicant/index.js'])
@endPushOnce

@pushOnce('pre-styles')

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/centre.css'])
@endPushOnce

@section('content')

<div class="row hidden-until-load">
    <div class="col-md-4 centre-side-nav fadein-text">
        <x-layout.centre.guest-nav-bar />
    </div>

    <div class="col-md-8 content-container">
        <main>

            <section aria-atomic="" id="default-landing">
                @include('help-centre.default.landing')
            </section>

            <!-- USING EMPLO -->

            <section class="content-section" id="about">
                @include('help-centre.usage.about')
            </section>

            <!-- Terms: Guest -->
            <section class="content-section" id="guest-terms-usage">
                @include('help-centre.usage.guest.terms-conditions')
            </section>

            <!-- PRIVACY: Guest -->
            <section class="content-section" id="guest-privacy-policy">
                @include('help-centre.usage.guest.privacy-policy')
            </section>

        </main>
    </div>
</div>

@endsection
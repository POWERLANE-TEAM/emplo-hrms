@extends('components.layout.centre.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
<title>Help Centre</title>
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

<div class="row">
    <div class="col-md-4 centre-side-nav fadein-text">
        <x-layout.centre.nav-bar />
    </div>

    <div class="col-md-8 content-container">
        <main>

            <section aria-atomic="" id="default-landing">
                @include('help-centre.default.landing')
            </section>

            <!-- Using Emplo -->
            <section class="content-section" id="terms-usage">
                @include('help-centre.usage.terms-conditions')
            </section>

            <section class="content-section" id="about-rankings">
                @include('help-centre.about.rankings')
            </section>


            <!-- System Policy -->
            <section class="content-section" id="privacy-policy">
                @include('help-centre.policy.system.privacy-policy')
            </section>

            <section class="content-section" id="data-retention">
                @include('help-centre.policy.system.data-retention')
            </section>


            <!-- Company Polcies -->
            <section class="content-section" id="seperation-process">
                @include('help-centre.policy.company.separation-process')
            </section>
            <section class="content-section" id="evaluation-policy">
                @include('help-centre.policy.company.evaluation')
            </section>
        </main>
    </div>
</div>

@endsection
@extends('components.layout.centre.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
<title>Help Centre</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
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

<div class="row centre-side-nav">
    <div class="col-md-4">
        <x-layout.centre.nav-bar/>
    </div>

    <div class="col-md-8">
        <main>

            <section class="" id="default-landing">
            Default Content highlight_file
            </section>

            <!-- About Emplo -->
            <section class="content-section" id="terms-usage">
                @include('help-centre.policy.company.separation-process')
            </section>

            <section class="content-section" id="system-policies">
                System Policy
            </section>

            <!-- Company Polcies -->
            <section class="content-section" id="seperation-process">
                Separation Process
            </section>
            <section class="content-section" id="data-retention">
                Data Retention Policy
            </section>
        </main>
    </div>

</div>

@endsection
@extends('components.layout.centre.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
<title>Applicant</title>
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
        <nav class="card border-primary px-4 py-4 gray-custom-scrollbar">
            <div>
                <section id="about-emplo" class="nav-section">
                    <header class="d-flex align-items-center">
                        <i class="icon icon-xxlarge p-1 mx-2 text-info" data-lucide="info"></i>
                        <span>About Emplo</span>
                    </header>
                    <section class="nav-items-section">
                        <div>
                            <div class="nav-item" data-target="terms-usage"
                                onclick="showContent('terms-usage', 'about-emplo')">
                                Terms of Usage
                            </div>
                        </div>
                        <div>
                            <div class="nav-item" data-target="system-policies"
                                onclick="showContent('system-policies', 'about-emplo')">
                                System Policies

                            </div>
                    </section>
                </section>

                <section id="company-policies" class="nav-section">
                    <header class="d-flex align-items-center">
                        <i class="icon icon-xxlarge p-1 mx-2 text-info" data-lucide="info"></i>
                        <span>Company Policies</span>
                    </header>
                    <section class="nav-items-section">
                        <div>
                            <div class="nav-item" data-target="seperation-process"
                                onclick="showContent('seperation-process', 'company-policies')">
                                Separation Process
                            </div>
                        </div>
                        <div>
                            <div class="nav-item" data-target="data-retention"
                                onclick="showContent('data-retention', 'company-policies')">
                                Data Retention Policy
                            </div>
                        </div>
                    </section>
                </section>
            </div>
        </nav>
    </div>

    <div class="col-md-8">
        <main>

            <section class="" id="default-landing">
            Default Content highlight_file
            </section>

            <!-- About Emplo -->
            <section class="content-section" id="terms-usage">
                Terms of Usage
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
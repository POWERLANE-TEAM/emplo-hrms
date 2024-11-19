@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')

@section('head')
<title>EMP #'s Performance Evaluation Results</title>
<script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
<script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/performance.js'])

@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/performance.css'])

@endPushOnce
@section('content')

<section class="mb-5">
    <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">

    </header>

    <!-- Main Section -->
    <div class="d-flex mb-5 row">
        <!-- Left Section: Overview -->
        <div class="col-md-5 d-flex">
            <div class="h-100 w-100">

                <!-- Navigation Tabs -->
                <div class="p-2">
                    @include('components.includes.tab_navs.eval-result-navs')
                </div>

                <!-- Navigation Tabs Content -->
                <div class="card border-primary mt-1 p-4 h-100 w-100">

                    <!-- Overview Tab Section-->
                    <section id="overview" class="tab-section">
                        Overview content goes here.
                    </section>

                    <!-- Da Records Tab Section -->
                    <section id="da-records" class="tab-section">
                        Da Records content goes here.
                    </section>

                    <!-- Attendance Tab Section -->
                    <section id="attendance" class="tab-section">
                        Attendance content goes here.
                    </section>

                    <!-- Comments Tab Section -->
                    <section id="comments" class="tab-section">
                        Comments content goes here.
                    </section>
                </div>
            </div>
        </div>

        <!-- Right Section: Performance Catgory -->
        <section class="col-md-7 d-flex">
            <div class="h-100 w-100">

                <!-- HEADER -->
                <div class="row px-3">

                    <!-- Header of the Performance Category -->
                    <div class=" col-8 d-flex align-items-center fw-bold" style="margin-right: 2em;">
                        <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>Performance Category
                    </div>

                    <div class="col-1 px-2 mr-3">
                        <div class="text-center fw-bold">3</div>
                        <div class="text-center text-muted fs-7">mos</div>
                    </div>

                    <!-- 5 months -->
                    <div class="col-1 px-2  mr-3">
                        <div class="text-center fw-bold">5</div>
                        <div class="text-center text-muted fs-7">mos</div>
                    </div>

                    <!-- Final -->
                    <div class="col-1 d-flex align-items-center">
                        <div class="text-center fw-bold text-primary justify-content-center">
                            Final
                        </div>
                    </div>

                </div>

                <!-- BACK-END Replace: Replace with evaluation results. -->

                @php
                    $evaluations = [
                        [
                            'title' => 'Quantity of Work',
                            'desc' => 'Consistently delivers high-quality work, meeting deadlines and completing tasks efficiently with minimal supervision.',
                            'three_month_score' => 3,
                            'five_month_score' => 5,
                            'final_score' => 4,
                        ],
                        [
                            'title' => 'Quality of Work',
                            'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
                            'three_month_score' => 4,
                            'five_month_score' => 4,
                            'final_score' => 5,
                        ],
                        [
                            'title' => 'Quality of Work',
                            'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
                            'three_month_score' => 4,
                            'five_month_score' => 4,
                            'final_score' => 5,
                        ],
                        [
                            'title' => 'Quality of Work',
                            'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
                            'three_month_score' => 4,
                            'five_month_score' => 4,
                            'final_score' => 5,
                        ],

                    ];
                @endphp


                <div class="scrollable-container">
                    <!-- Results -->
                    @foreach($evaluations as $evaluation)
                        <div class="card p-4 my-4 d-flex">
                            <div class="row px-3">
                                <!-- Title and Description -->
                                <div class="col-7">
                                    <p class="fw-bold fs-5 text-primary">{{ $loop->iteration }}. {{ $evaluation['title'] }}
                                    </p>
                                    <p>{{ $evaluation['desc'] }}</p>
                                </div>

                                <!-- Vertical Line -->
                                <div class="col-2 d-flex justify-content-center">
                                    <div class="vertical-line"></div>
                                </div>

                                <!-- 3 Month Score -->
                                <div class="col-1 px-2 d-flex align-items-center">
                                    <div class="text-center fw-bold">{{ $evaluation['three_month_score'] }}</div>
                                </div>

                                <!-- 5 Month Score -->
                                <div class="col-1 px-2 d-flex align-items-center">
                                    <div class="text-center fw-bold">{{ $evaluation['five_month_score'] }}</div>
                                </div>

                                <!-- Final Score -->
                                <div class="col-1 d-flex align-items-center">
                                    <div class="text-center fw-bold text-primary justify-content-center">
                                        {{ $evaluation['final_score'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


            </div>
        </section>

        @endsection
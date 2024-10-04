@php
    $nonce = csp_nonce();
@endphp

@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce, 'user' => $user])

@section('head')
    <title>Home Page</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
@endsection

@pushOnce('pre-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js" nonce="{{ $nonce }}"></script>
@endPushOnce

@pushOnce('scripts')
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/hr/dashboard.js'])
@endPushOnce
@pushOnce('styles')
    @vite(['resources/css/employee/dashboard.css'])
@endPushOnce
@section('content')
    <hgroup class="mb-5 ms-n1">
        <div class="fs-2 fw-bold mb-2">Good afternoon, {{ $user->account->first_name }}!</div>
        <p>It is <time datetime="{{ now() }}"> {{ \Carbon\Carbon::now()->format('l, d F') }}</time></p>
    </hgroup>
    <section role="navigation" aria-label="Quick Links" class=" mb-5 d-flex gap-5">
        <div class="card bg-body-secondary col-md-4 border-0 px-md-5 py-4 " role="none"
            aria-describedby="applicants-nav-desc">
            <x-nav-link href="#" class="unstyled">
                <div class="mb-3">
                    <span></span>
                    <div class="fs-4 fw-bold">Applicants</div>
                </div>
                <div class="card-text" id="applicants-nav-desc">
                    Review and verify candidates, resume and documents.
                </div>
            </x-nav-link>
        </div>

        <div class="card bg-primary text-white col-md-4  border-0 px-md-5 py-4" role="none"
            aria-describedby="leaves-nav-desc">
            <x-nav-link href="#" class="unstyled">
                <div class="mb-3">
                    <span></span>
                    <div class="fs-4 fw-bold text-white">Leaves</div>
                </div>
                <div class="card-text text-white" id="leaves-nav-desc">
                    Track pending leave request, its leave type and approval.
                </div>
            </x-nav-link>
        </div>

        <div class="card bg-body-secondary col-md-4  border-0 px-md-5 py-4" role="none"
            aria-describedby="attendance-nav-desc">
            <x-nav-link href="#" class="unstyled">
                <div class="mb-3">
                    <span></span>
                    <div class="fs-4 fw-bold">Attendance</div>
                </div>

                <div class="card-text" id="attendance-nav-desc">
                    Monitor employees attendance, absence and workday hours.
                </div>
            </x-nav-link>
        </div>


    </section>

    <section class="mb-5">
        <header class="fs-4 fw-bold mb-4" role="heading" aria-level="2">
            Key Metrics
        </header>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const absenceRatelabels = [
                    'July',
                    'August', 'September', 'October', 'November', 'December'
                ];
                const absenceRateData = {
                    labels: absenceRatelabels,
                    datasets: [{
                        label: '',
                        data: [8, 14, 9, 15, 19, 20, 10],
                        fill: false,
                        borderColor: 'rgb(117, 73, 255)',
                        tension: 1
                    }],
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            // tooltip: {
                            //     callbacks: {
                            //         label: ((tooltipItem, data) => {
                            //             console.log(tooltipItem)
                            //             return 'Rate';
                            //         })
                            //     }
                            // }

                        }

                    },
                };

                const empAbsenceRateConfig = {
                    type: 'line',
                    data: absenceRateData,
                };

                // let empAbsenceRateChart = document.getElementById('hr-absence-rate')

                const absenceRateChart = new Chart('hr-absence-rate', empAbsenceRateConfig);

                const labels = [
                    ''
                ]


                const Utils = {
                    numbers: (cfg) => {
                        // Your logic to generate numbers based on cfg
                        return [10, 20, 30, 40, 50]; // Example data
                    },
                    CHART_COLORS: {
                        red: 'rgba(255, 99, 132, 0.2)',
                        blue: 'rgba(54, 162, 235, 0.2)',
                        green: 'rgba(75, 192, 192, 0.2)'
                    }
                };

                const DATA_COUNT = 7;
                const NUMBER_CFG = {
                    count: DATA_COUNT,
                    min: -100,
                    max: 100
                };


                const data = {
                    labels: labels,
                    datasets: [{
                            label: 'Absent ',
                            data: Utils.numbers(NUMBER_CFG),
                            backgroundColor: Utils.CHART_COLORS.red,
                        },
                        {
                            label: 'Present ',
                            data: Utils.numbers(NUMBER_CFG),
                            backgroundColor: Utils.CHART_COLORS.blue,
                        },
                        {
                            label: 'On leave',
                            data: Utils.numbers(NUMBER_CFG),
                            backgroundColor: Utils.CHART_COLORS.green,
                        },
                    ]
                };

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: ''
                            },
                        },
                        responsive: true,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                };



                const attendance = new Chart('hr-attendance', config);
            });
        </script>

        <div class="d-flex gap-5 mb-5">
            <div class="col-md-7">

                <div class="col-12">
                    <div class="col-md-3 ms-auto">
                        <select name="" id="" class="bg-primary form-select "
                            aria-label="Default select example"style="--bs-bg-opacity: .25;">
                            <option value="">September</option>
                        </select>
                    </div>
                </div>

                <ul class="nav nav-underline">
                    <li class="nav-item" id="emp-satisf-tab" data-bs-toggle="tab" data-bs-target="#emp-satisf-tab-pane"
                        role="tab" aria-controls="emp-satisf-tab-pane" aria-selected="true">
                        <a class="nav-link " href="#">Employee Satisfaction</a>
                    </li>
                    <li class="nav-item" id="absence-rate-tab" data-bs-toggle="tab" data-bs-target="#absence-rate-tab-pane"
                        role="tab" aria-controls="absence-rate-tab-pane" aria-selected="false">
                        <a class="nav-link active" href="#">Absence Rate</a>
                    </li>
                    <li class="nav-item" id="training-compl-tab" data-bs-toggle="tab"
                        data-bs-target="#training-compl-tab-pane" role="tab" aria-controls="training-compl-tab-pane"
                        aria-selected="false">
                        <a class="nav-link " href="#">Training Copmpletion</a>
                    </li>
                </ul>

                <div class="tab-content" id="hr-dashboard-stats">
                    <div class="tab-pane fade " id="emp-satisf-tab-pane" role="tabpanel" aria-labelledby="emp-satisf-tab"
                        tabindex="0">...</div>
                    <div class="tab-pane fade show active" id="absence-rate-tab-pane" role="tabpanel"
                        aria-labelledby="absence-rate-tab" tabindex="0">
                        <canvas id="hr-absence-rate"></canvas>
                    </div>
                    <div class="tab-pane fade " id="training-compl-tab-pane" role="tabpanel"
                        aria-labelledby="training-compl-tab" tabindex="0">...</div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="mb-4">
                    <canvas id="hr-attendance"></canvas>
                </div>
                <div class=" card  p-4">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                            </tr>
                        </thead>

                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td>{{ fake()->name() }}</td>
                                    <td>{{ fake()->time() }}</td>
                                    <td>{{ fake()->time() }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </section>

    <section class="mb-5">
        <header class="fs-4 fw-bold text-primary mb-4" role="heading" aria-level="2">
            <span>
                <picture>
                    <source media="(min-width:2560px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-xxl.webp') }}">
                    <source media="(min-width:768px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-md.webp') }}">
                    <source media="(min-width:576px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-sm.webp') }}">
                    <source media="(max-width:320px)" class=""
                        srcset="{{ Vite::asset('resources/images/icons/green-calendar-xs.webp') }}">

                    <img width="28" height="28" aspect-ratio="1/1" class="icon" loading="lazy"
                        src="{{ Vite::asset('resources/images/icons/green-calendar-md.webp') }}" alt="">
                </picture>
            </span>
            <span class="fs-4 fw-bold ps-1 pe-3">Employee Statistics</span>
        </header>

        <div class="d-flex">
            <div class="d-flex col-6 flex-md-wrap gap-3">
                <div class="card col-md-5 text-center p-3">
                    <div class="fs-5 fw-bold">128</div>
                    <small>Total Employment</small>
                </div>
                <div class="card col-md-5 text-center p-3">
                    <div class="fs-5 fw-bold">128</div>
                    <small>New Hires</small>
                </div>
                <div class="card col-md-5 text-center p-3">
                    <div class="fs-5 fw-bold">128</div>
                    <small>Departure</small>
                </div>
                <div class="card col-md-5 text-center p-3">
                    <div class="fs-5 fw-bold">128</div>
                    <small>On Probationary</small>
                </div>
            </div>
            <div class="col-6 card border-primary p-5">
                <div class="fs-3 fw-bold mb-4">Reminders</div>
                <div class="d-table w-100">
                    <div class="d-table-row">
                        <div class="d-table-cell">Next Payslip Uploading</div>
                        <div class="d-table-cell">Due this Friday</div>
                    </div>
                    <div class="d-table-row">
                        <div class="d-table-cell">Performance Evaluation</div>
                        <div class="d-table-cell">Due this Friday</div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

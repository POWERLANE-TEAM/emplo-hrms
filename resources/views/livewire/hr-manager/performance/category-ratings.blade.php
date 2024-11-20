<!-- Header -->
<div>
    <div class="row px-3 mb-4">

        <div class=" col-8 d-flex align-items-center fw-bold" style="margin-right: .7em;">
            <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>Performance Category
        </div>

        <!-- 3 months -->
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
            [
                'title' => 'Quality of Work',
                'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
                'three_month_score' => 4,
                'five_month_score' => 4,
                'final_score' => 5,
            ],

        ];
    @endphp


    <div class="scrollable-container visible-gray-scrollbar">
        <!-- Results -->
        @foreach($evaluations as $evaluation)
            <div class="card p-4 mb-4 d-flex">
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
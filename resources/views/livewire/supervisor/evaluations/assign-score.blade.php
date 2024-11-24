<!-- Header -->
<div>
    <div class="row px-3 mb-4">

        <div class=" col-6 d-flex align-items-center fw-bold">
            <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>Performance Category
        </div>

        <!-- 3 months -->
        <div class="col-2">
            <div class="text-center fw-bold">3</div>
            <div class="text-center text-muted fs-7">mos</div>
        </div>

        <!-- 5 months -->
        <div class="col-2 mr-1">
            <div class="text-center fw-bold">5</div>
            <div class="text-center text-muted fs-7">mos</div>
        </div>

        <!-- Final -->
        <div class="col-2 pe-5 d-flex align-items-center justify-content-center">
    <div class="fw-bold text-primary">
        Final
    </div>
</div>

    </div>

    <!-- BACK-END Replace: Replace with evaluation results.
        Note that The other months will be disabled until
        it's time for the 5-month or final evaluation.-->

    @php
        $evaluations = [
            [
                'title' => 'Quantity of Work',
                'desc' => 'Consistently delivers high-quality work, meeting deadlines and completing tasks efficiently with minimal supervision.',
            ],
            [
                'title' => 'Quality of Work',
                'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
            ],
            [
                'title' => 'Quality of Work',
                'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
            ],
            [
                'title' => 'Quality of Work',
                'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
            ],
            [
                'title' => 'Quality of Work',
                'desc' => 'Demonstrates attention to detail and ensures that all outputs meet quality standards.',
            ],

        ];
    @endphp


    <!-- Results -->
    <!-- BACK-END Replace: Replace options of the dropdown.-->
    @foreach($evaluations as $evaluation)
        <div class="card p-4 mb-4 d-flex">
            <div class="row px-3">
                <!-- Title and Description -->
                <div class="col-5">
                    <p class="fw-bold fs-5 text-primary">{{ $loop->iteration }}. {{ $evaluation['title'] }}</p>
                    <p>{{ $evaluation['desc'] }}</p>
                </div>

                <!-- Vertical Line -->
                <div class="col-1 d-flex justify-content-center">
                    <div class="vertical-line"></div>
                </div>

                <!-- Replace Scores with Dropdown -->
                <div class="col-6 d-flex justify-content-around">
                    <!-- 3 Month Score Dropdown -->
                    <div class="col-3 px-2 d-flex align-items-center">
                    <x-form.boxed-dropdown id="3_mos_score_{{ $loop->iteration }}"
                        wire:model="evaluations.{{ $loop->iteration }}.three_month_score" :nonce="$nonce"
                        :options="[1, 2, 3, 4, 5]" placeholder="Select score">
                    </x-form.boxed-dropdown>
                    </div>

                    <!-- 5 Month Score Dropdown -->
                    <div class="col-3 px-2 d-flex align-items-center">
                    <x-form.boxed-dropdown id="5_mos_score_{{ $loop->iteration }}"
                        wire:model="evaluations.{{ $loop->iteration }}.five_month_score" :nonce="$nonce"
                        :options="[1, 2, 3, 4, 5]" placeholder="Select score">
                    </x-form.boxed-dropdown>
                    </div>

                    <!-- Final Score Dropdown -->
                    <div class="col-3 px-2 d-flex align-items-center">
                    <x-form.boxed-dropdown id="final_score_{{ $loop->iteration }}"
                        wire:model="evaluations.{{ $loop->iteration }}.final_score" :nonce="$nonce"
                        :options="[1, 2, 3, 4, 5]" placeholder="Select score">
                    </x-form.boxed-dropdown>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div>
    <!-- Selection of the Job Position -->
    <section>
        <div class="col-md-12">
            <div class="card bg-body-secondary border-0 py-4 px-4">

                <div class="row">
                    <div class="col-md-3 d-flex align-items-center">
                        <div><i data-lucide="briefcase-business" class="icon icon-slarge me-2 text-"> </i>Please select first the job position: </div>
                    </div>

                    <div class="col-md-5 d-flex align-items-center">
                        <!-- Selectpicker -->
                        <x-form.boxed-selectpicker id="selected_position" :nonce="$nonce" class="mb-0" :required="true"
                            :options="['1' => 'Data Analyst', '2' => 'HR Manager', '3' => 'Accountant', '4' => 'IT']"
                            placeholder="Select Job Position" wire:model="selectedJobPosition">
                        </x-form.boxed-selectpicker>
                    </div>

                    <div class="col-md-4 d-flex align-items-center">
                        <!-- Submit Button: Trigger the 'generate rankings' action when clicked -->
                        <x-buttons.main-btn label="Generate Rankings" :nonce="$nonce" class="w-50"
                            :loading="'Submitting...'" wire:click="generateRankings" :disabled="false" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rankings Table -->
    <section>
        <div class="col-md-12">
            @if ($selectedJobPosition !== null)
                @if (count($applicantsData) > 0)

                    <section wire:loading.remove>

                        <!-- Callout: Total applicants & Job Position -->
                        <x-headings.sparkle-callout>
                            <x-slot:description>
                                Found <span class="fw-bold text-primary">{{ $totalApplicants }}</span> candidates who are
                                applying
                                for the job position of <span class="fw-bold text-primary">{{ $selectedPositionName }}</span>.
                                Check
                                out their scores below!
                            </x-slot:description>
                        </x-headings.sparkle-callout>

                        <!-- Table: Rankings of Applicants -->
                        <section class="mt-2">
                            <div class="table-wrapper">
                                <table class="col-md-12">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Score</th>
                                            <th class="text-center">Applicant Name</th>
                                            <th class="text-center">Qualification(s) Met</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applicantsData as $index => $applicant)
                                            <tr class="pb-5 {{ $index === 0 ? 'active' : '' }}">
                                                <!-- Score -->
                                                <td class="text-center">
                                                    <span class="resume-score-circle">{{ $applicant['percentage'] }}%</span>
                                                </td>

                                                <!-- Name & Email -->
                                                <td class="text-center">
                                                    <span class="fs-5 fw-bold">{{ $applicant['name'] }}</span><br>
                                                    <span>{{ $applicant['email'] }}</span>
                                                </td>

                                                <!-- Qualification(s) Met -->
                                                <td class="qualifications pe-4">
                                                    <span class="qualifications-text"><!--  {{ $applicant['qualifications_met'] }} Count of total qualifications passed. Temporarily commented this out.-->
                                                        {{ $applicant['qualifications_list'] }}
                                                    </span>
                                                    <span class="see-more hover-opacity" onclick="toggleText(this)">See More</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </section>
                @else
                    <!-- Empty State: No Applicants -->
                    <div wire:loading.remove>
                        <div class="empty-state d-flex justify-content-center align-items-center text-center w-100 h-100 py-5">
                            <div class="mt-4">
                                <img class="img-size-20 img-responsive"
                                    src="{{ Vite::asset('resources/images/illus/empty-states/no-docs-found.gif') }}" alt="">
                                <p class="fs-7 pt-4">No applicants yet for this job position.</p>
                            </div>
                        </div>
                    </div>
                @endif

            <!-- Default Empty State -->
            @else
                <div wire:loading.remove>
                    <div class="empty-state d-flex justify-content-center align-items-center text-center w-100 h-100 py-5">
                        <div>
                            <img class="img-size-20 img-responsive"
                                src="{{ Vite::asset('resources/images/illus/empty-states/personal-data.webp') }}" alt="">
                            <p class="fs-7 pt-4">Rankings of applicants will be displayed here once generated.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Skeleton Loading -->
            <div wire:loading>
                @include('livewire.placeholder.evaluator')
            </div>
        </div>


    </section>
</div>

@script
<script>
    Livewire.hook('morph.added', ({ el }) => {
        lucide.createIcons();
    });

    Livewire.hook('morph.added', ({ el }) => {
        console.log('Morph added hook triggered');

        setTimeout(() => {
            console.log("Checking overflow...");
            checkOverflow();
        }, 200);
    });

</script>
@endscript
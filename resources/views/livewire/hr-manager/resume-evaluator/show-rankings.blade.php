<div class="mt-2">

    <!-- Selection of the Job Position -->
    <section>
        <div class="col-md-12">
            <div class="card bg-body-secondary border-0 py-4 px-4">

                <div class="row">
                    <div class="col-md-3">
                        <p>Please select first the job position: </p>
                    </div>

                    <div class="col-md-3">
                        <!-- Selectpicker -->
                        <x-form.boxed-selectpicker id="selected_position" :nonce="$nonce" :required="true"
                            :options="['1' => 'Data Analyst', '2' => 'HR Manager', '3' => 'Accountant']"
                            placeholder="Select Job Position" wire:model="selectedJobPosition">
                        </x-form.boxed-selectpicker>
                    </div>

                    <div class="col-md-6">
                        <!-- Submit Button: Trigger the 'generate rankings' action when clicked -->
                        <x-buttons.main-btn label="Generate Rankings" :nonce="$nonce" class="w-50"
                            :loading="'Submitting...'" wire:click="generateRankings" :disabled="false" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>

        <div class="col-md-12">
            @if($selectedJobPosition !== null)

                <x-headings.sparkle-callout>
                    <x-slot:description>
                        Found <span class="fw-bold text-primary">{{ $totalApplicants }}</span> candidates who are applying
                        for the job position of <span class="fw-bold text-primary">{{ $selectedPositionName }}</span>. Check
                        out their scores below!
                    </x-slot:description>
                </x-headings.sparkle-callout>

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
                                @foreach ($applicantsData as $applicant)
                                    <tr class="pb-5 active">
                                        <!-- Score -->
                                        <td class="text-center"><span
                                                class="resume-score-circle">{{ $applicant['percentage'] }}%</span></td>

                                        <!-- Name & Email -->
                                        <td class="text-center">
                                            <span class="fs-5 fw-bold">{{ $applicant['name'] }}
                                            </span><br>
                                            <span>
                                                {{ $applicant['email'] }}
                                            </span>
                                        </td>

                                        <!-- Qualification(s) Met -->
                                        <td wire:ignore class="qualifications pe-4">
                                            <span class="qualifications-text">{{ $applicant['qualifications_met'] }}
                                                -{{ $applicant['qualifications_list'] }}
                                            </span>
                                            <span class="see-more hover-opacity" onclick="toggleText(this)">See More</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @else
                <p>Please select a job position.</p>
            @endif
        </div>
    </section>
</div>

@script
<script>
    Livewire.hook('morph.added', ({ el }) => {
        lucide.createIcons();
    });
</script>
@endscript
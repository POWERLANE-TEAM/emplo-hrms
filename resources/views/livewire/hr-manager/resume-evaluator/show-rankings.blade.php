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
                <table>
                    <thead>
                        <tr>
                            <th>Percentage (%)</th>
                            <th>Applicant Name</th>
                            <th>Qualification(s) Met</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicantsData as $applicant)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $applicant['percentage'] }}%</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $applicant['name'] }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $applicant['qualifications_met'] }} -
                                    {{ $applicant['qualifications_list'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Please select a job position.</p>
            @endif
        </div>
    </section>

</div>
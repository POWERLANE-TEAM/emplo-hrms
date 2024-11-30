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
                        <x-form.boxed-dropdown id="leave_type" name="leave_type" :nonce="$nonce" :options="['Sick' => 'Sick', 'Annual' => 'Annual', 'Weekly' => 'Weekly']" placeholder="Select type" />
                    </div>

                    <div class="col-md-6">
                        <x-buttons.main-btn label="Generate Rankings" :nonce="$nonce" :disabled="false" class="w-50"
                            :loading="'Submitting...'" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
    <table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr>
            <th class="border border-gray-300 px-4 py-2 text-left">Percentage (%)</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Applicant Name</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Qualification(s) Met</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicantsData as $applicant)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $applicant['percentage'] }}%</td>
                <td class="border border-gray-300 px-4 py-2">{{ $applicant['name'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $applicant['qualifications_met'] }} - {{ $applicant['qualifications_list'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>

    

    


</div>
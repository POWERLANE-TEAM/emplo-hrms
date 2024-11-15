<div>
    <select id="job_position" class="form-control form-select" wire:model="selectedJobId"
        wire:change="updateJobPosition($event.target.value)">
        <option value="">Select Job Position</option>
        <option value="1">Accountant</option>
        <option value="2">HR Manager</option>
        <option value="3">Marketer</option>
        <option value="4">Data Analyst</option>
    </select>

    @if($selectedJob)
        <header class="pt-5">
            <x-headings.section-title title="Job Information" />
        </header>

        <p><strong>Department:</strong> {{ $selectedJob['dept'] }}</p>
        <p><strong>Job Description:</strong> {{ $selectedJob['job_desc'] }}</p>

        <p><strong>Qualifications:</strong></p>
        <ul>
            @foreach($selectedJob['Qualifications'] as $qualification)
                <li>{{ $qualification }}</li>
            @endforeach
        </ul>

        {{-- Submit Button --}}
        <section class="my-2"></section>
        <x-buttons.main-btn id="add_open_position" label="Add Open Job Position" name="add_open_position" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
        </section>
    @else
        <p class="pt-3 fst-italic">Please select a job position to see the details.</p>
    @endif
</div>
@props(['toastId' => 'jobListingToast'])

<div>
    <div class="row mb-3">
        <div class="col-4">
            <div class="mb-1 fw-semibold text-secondary-emphasis">{{ __('Job Position') }}</div>
            <select id="job_position" class="form-control form-select" wire:model.live="state.selectedJob">
                <option value="">{{ __('Select an option') }}</option>
                @foreach ($this->jobTitles as $jobTitle)
                    <option value="{{ $jobTitle->id }}">{{ $jobTitle->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <x-form.boxed-input-text
                id="vacancyCount"
                label="{{ __('Vacancy Count') }}"
                name="state.vacancyCount" 
                type="number"
                :nonce="$nonce" 
                :required="true" 
            />
            @error('state.vacancyCount')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <x-form.boxed-date 
                id="applicationDeadline"
                label="{{ __('Application Deadline') }}" 
                name="state.applicationDeadline" 
                :nonce="$nonce"
                :required="true" 
                placeholder=""
            />
            @error('state.applicationDeadline')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if($isJobSelected)
        <header class="mt-2">
            <x-headings.section-title title="{{ __('Job Information') }}" />
        </header>

        @if ($jobDetails)
            @foreach ($jobDetails as $jobDetail)
                <p><strong>{{ __('Department: ') }}</strong>{{ $jobDetail->department }}</p>
                <p><strong>{{ __('Job Family: ') }}</strong>{{ $jobDetail->family }}</p>
                <p><strong>{{ __('Job Level: ') }}</strong>{{ $jobDetail->levelName.' (Level '.$jobDetail->level.')' }}</p>
                <p><strong>{{ __('Job Description: ') }}</strong>{{ $jobDetail->description ?? __('Not specified') }}</p>
                <p><strong>{{ __('Qualifications: ') }}</strong></p>
                <ul>
                    @if ($jobDetail->qualifications)
                        @foreach($jobDetail->qualifications as $qualification)
                            <li>{{ $qualification }}</li>
                        @endforeach                        
                    @endif
                </ul>
            @endforeach
        @endif

        {{-- Submit Button --}}
        <section class="my-2"></section>
        <x-buttons.main-btn 
            id="add_open_position" 
            label="Add Open Job Position" 
            wire:click="save" 
            :nonce="$nonce"
            :disabled="false" 
            class="w-25" 
            :loading="'Creating...'" 
        />
        </section>
    @else
        <p class="pt-3 fst-italic">{{ __('Please select a job position to see the details.') }}</p>
    @endif

    {{-- Toast --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="{{ $toastId }}" class="toast text-bg-primary text-white top-25 end-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const toastEl = document.getElementById('{{ $toastId }}');
    const toast = new bootstrap.Toast(toastEl);

    Livewire.on('changes-saved', (event) => {
        if (event[0].success) {
            setTimeout(() => {
                toastEl.querySelector('.toast-body').textContent = event[0].message;
                toast.show();
            }, 1000);  
        } else {
            setTimeout(() => {
                toastEl.classList.replace('text-bg-primary', 'text-bg-danger');
                toastEl.querySelector('.toast-body').textContent = event[0].message;
                toast.show();
            }, 1000);  
        }  
    });
</script>
@endscript
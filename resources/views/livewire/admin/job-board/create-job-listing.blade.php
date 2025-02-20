@use(App\Enums\JobQualificationPriorityLevel)
@props(['toastId' => 'jobListingToast'])

<div>
    <div class="row">
        <div class="col-4">
            <div class="row mb-3">
                <div class="col">
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
                <div class="col">
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
                <div class="col">
                    <x-form.boxed-date 
                        id="applicationDeadline"
                        label="{{ __('Application Deadline') }}" 
                        name="state.applicationDeadline" 
                        :nonce="$nonce"
                        :required="true" 
                        placeholder=""
                        min="{{ today()->format('Y-m-d') }}"
                    />
                    @error('state.applicationDeadline')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>                    
        </div>

        <div class="col-3">
            @if($isJobSelected)
                <header class="mt-2">
                    <x-headings.section-title title="{{ __('Job Information') }}" />
                </header>

                @foreach ($jobDetails as $jobDetail)
                    <p><strong>{{ __('Department: ') }}</strong>{{ $jobDetail->department }}</p>
                    <p><strong>{{ __('Job Family: ') }}</strong>{{ $jobDetail->family }}</p>
                    <p><strong>{{ __('Job Level: ') }}</strong>{{ $jobDetail->levelName.' (Level '.$jobDetail->level.')' }}</p>
                    <p><strong>{{ __('Job Description: ') }}</strong>{{ $jobDetail->description ?? __('Not specified') }}</p>
                @endforeach

                <x-buttons.main-btn 
                    id="add_open_position" 
                    label="Add Open Job Position" 
                    wire:click="save" 
                    :nonce="$nonce"
                    :disabled="false" 
                    class="w-25" 
                    :loading="'Creating...'" 
                />
            @else
                <p class="pt-3 fst-italic">{{ __('Please select a job position to see the details.') }}</p>
            @endif
        </div>

        @if($isJobSelected)
            @foreach ($jobDetails as $jobDetail)
                <div class="col-5 text-start">
                    <header class="mt-2">
                        <x-headings.section-title title="{{ __('Qualifications') }}" />
                    </header>
                    <p><strong>{{ __('Skills: ') }}</strong></p>
                    <ul>
                        @if ($jobDetail->skills)
                            @foreach($jobDetail->skills as $skill)
                                <div class="d-flex align-items-center my-2">
                                    <li class="pe-2 fw-medium">{{ $skill->keyword }}</li>
                                    <span>
                                        @php $priority = JobQualificationPriorityLevel::from($skill->priority); @endphp

                                        <x-status-badge :color="$priority->getColor()">
                                            {{ $priority->label() }}
                                        </x-status-badge>
                                    </span>                                    
                                </div>
                            @endforeach                        
                        @endif
                    </ul>
                    <p><strong>{{ __('Educations: ') }}</strong></p>
                    <ul>
                        @if ($jobDetail->educations)
                            @foreach($jobDetail->educations as $education)
                                <div class="d-flex align-items-center my-2">
                                    <li class="pe-2 fw-medium">{{ $education->keyword }}</li>
                                    <span>
                                        @php $priority = JobQualificationPriorityLevel::from($education->priority); @endphp

                                        <x-status-badge :color="$priority->getColor()">
                                            {{ $priority->label() }}
                                        </x-status-badge>
                                    </span>                                    
                                </div>
                            @endforeach                        
                        @endif
                    </ul>
                    <p><strong>{{ __('Experiences: ') }}</strong></p>
                    <ul>
                        @if ($jobDetail->experiences)
                            @foreach($jobDetail->experiences as $experience)
                                <div class="d-flex align-items-center my-2">
                                    <li class="pe-2 fw-medium">{{ $experience->keyword }}</li>
                                    <span>
                                        @php $priority = JobQualificationPriorityLevel::from($experience->priority); @endphp

                                        <x-status-badge :color="$priority->getColor()">
                                            {{ $priority->label() }}
                                        </x-status-badge>
                                    </span>                                    
                                </div>
                            @endforeach                        
                        @endif
                    </ul>
                </div>
            @endforeach
        @endif
    </div>

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
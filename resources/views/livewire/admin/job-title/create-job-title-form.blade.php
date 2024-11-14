@props([
    'success' => __('Job title created successfully.'),
    'alert' => false,
])

<section>
    <div>
        {{-- Department --}}
        <div class="row">
            <div class="col">
                <x-form.boxed-dropdown 
                    id="dept" label="{{ __('Department') }}" 
                    wire:model="department" 
                    :nonce="$nonce" 
                    :required="true"
                    :options="$this->departments"
                >
                </x-form.boxed-dropdown>
                @error('department')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Job Title --}}
        <div class="row">
            <div class="col">
                <x-form.boxed-input-text 
                    id="job_position" label="{{ __('Job Title') }}" 
                    name="jobTitleName" 
                    :nonce="$nonce"
                    :required="true" 
                    placeholder="Enter job position" 
                />
                @error('jobTitleName')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Textarea field for: Job Title Description --}}
        <x-form.boxed-textarea 
            id="job_desc" 
            label="{{ __('Job Title Description') }}" 
            name="jobTitleDesc" 
            :nonce="$nonce" 
            :rows="6"
            placeholder="Enter description for the job position..." 
        />
        @error('jobTitleDesc')
            <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Qualifications Section --}}
    <div>
        <x-headings.form-snippet-intro label="{{ __('Qualification') }}" :nonce="$nonce" required="true"
            description="{{ __('Set the job title qualifications/requirements here.') }}">

            <x-tooltips.modal-tooltip icon="help-circle" color="text-info" modalId="editModalId" />

        </x-headings.form-snippet-intro>

        <livewire:blocks.dragdrop.show-qualifications />

        <livewire:blocks.inputs.qualification-input />
    </div>

    {{-- Submit Button --}}
    <div class="my-4">
        <x-buttons.main-btn id="create_position" label="Create Job Title" wire:click="save" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
    </div>   

    <span 
        x-data="{ alert: false }"
        x-cloak
        x-on:job-title-created.window="alert = true; setTimeout(() => { alert = false }, 2000)"
        x-show.transition.out.opacity.duration.1500ms="alert"
        x-transition:leave.opacity.duration.1500ms
        x-show="alert"
        class="fw-bold text-success"
    >
        {{ $success }}
    </span>
</section>

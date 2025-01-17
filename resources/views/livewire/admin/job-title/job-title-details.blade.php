@props([
    'success' => __('Job title created successfully.'),
    'alert' => false,
])

<section>
    <div>
        <div class="row">
            <div class="col">
                <x-form.boxed-dropdown id="family" label="{{ __('Job Family') }}" wire:model="family"
                    :nonce="$nonce" :required="true" :options="$this->jobFamilies">
                </x-form.boxed-dropdown>
                @error('family')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-dropdown id="level" label="{{ __('Job Level') }}" wire:model="level" :nonce="$nonce"
                    :required="true" :options="$this->jobLevels">
                </x-form.boxed-dropdown>
                @error('level')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-dropdown id="dept" label="{{ __('Department') }}" wire:model="department"
                    :nonce="$nonce" :required="true" :options="$this->departments">
                </x-form.boxed-dropdown>
                @error('department')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Job Title --}}
        <div class="row">
            <div class="col-7">
                <x-form.boxed-input-text id="job_position" label="{{ __('Job Title') }}" name="title"
                    :nonce="$nonce" :required="true" placeholder="Enter job title" />
                @error('title')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-5">
                <x-form.boxed-input-text type="number" id="salary" label="{{ __('Base Salary') }}" name="baseSalary"
                    :nonce="$nonce" placeholder="Enter base salary" />
                @error('baseSalary')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Textarea field for: Job Title Description --}}
        <x-form.boxed-textarea id="job_desc" label="{{ __('Job Title Description') }}" name="description"
            :nonce="$nonce" :rows="6" placeholder="Enter description for the job position..." />
        @error('description')
            <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Qualifications Section --}}
    <div class="mt-4">
        <header>
            <p class="mb-0 fs-4 text-primary fw-bold">Qualification</p>
            <p>Set the job title qualifications/requirements here.
                <a href="/information-centre?section=about-rankings" target="blank" class="tooltip-link">
                    <x-tooltips.custom-tooltip title="Learn more" icon="info" placement="top" color="text-info" />
                </a>
        </header>

        <livewire:admin.job-title.edit-education :$jobTitle />
        <livewire:admin.job-title.edit-skills :$jobTitle />
        <livewire:admin.job-title.edit-experiences :$jobTitle />
    </div>

    {{-- Submit Button --}}
    <div class="my-4">
        <x-buttons.main-btn id="create_position" label="Update Job Title" wire:click="update" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
    </div>

    <span x-data="{ alert: false }" x-cloak
        x-on:job-title-created.window="alert = true; setTimeout(() => { alert = false }, 2000)"
        x-show.transition.out.opacity.duration.1500ms="alert" x-transition:leave.opacity.duration.1500ms x-show="alert"
        class="fw-bold text-success">
        {{ $success }}
    </span>
</section>
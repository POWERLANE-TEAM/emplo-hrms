@props([
    'success' => __('Job title created successfully.'),
    'alert' => false,
])

<section>
    <div>
        <div class="row">
            <div class="col">
                <x-form.boxed-dropdown id="family" label="{{ __('Job Family') }}" wire:model="state.family"
                    :nonce="$nonce" :required="true" :options="$this->jobFamilies">
                </x-form.boxed-dropdown>
                @error('state.family')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-dropdown id="level" label="{{ __('Job Level') }}" wire:model="state.level" :nonce="$nonce"
                    :required="true" :options="$this->jobLevels">
                </x-form.boxed-dropdown>
                @error('state.level')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <x-form.boxed-dropdown id="dept" label="{{ __('Department') }}" wire:model="state.department"
                    :nonce="$nonce" :required="true" :options="$this->departments">
                </x-form.boxed-dropdown>
                @error('state.department')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Job Title --}}
        <div class="row">
            <div class="col-7">
                <x-form.boxed-input-text id="job_position" label="{{ __('Job Title') }}" name="state.title"
                    :nonce="$nonce" :required="true" placeholder="Enter job title" />
                @error('state.title')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-5">
                <x-form.boxed-input-text id="salary" label="{{ __('Base Salary') }}" name="state.baseSalary"
                    :nonce="$nonce" placeholder="Enter base salary" />
                @error('state.baseSalary')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Textarea field for: Job Title Description --}}
        <x-form.boxed-textarea id="job_desc" label="{{ __('Job Title Description') }}" name="state.description"
            :nonce="$nonce" :rows="6" placeholder="Enter description for the job position..." />
        @error('state.description')
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

        <livewire:admin.job-title.set-education />
        <livewire:admin.job-title.set-skills />
        <livewire:admin.job-title.set-experience />
    </div>

    {{-- Submit Button --}}
    <div class="my-4">
        <x-buttons.main-btn id="create_position" label="Create Job Title" wire:click="save" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Creating...'" />
    </div>

    <span x-data="{ alert: false }" x-cloak
        x-on:job-title-created.window="alert = true; setTimeout(() => { alert = false }, 2000)"
        x-show.transition.out.opacity.duration.1500ms="alert" x-transition:leave.opacity.duration.1500ms x-show="alert"
        class="fw-bold text-success">
        {{ $success }}
    </span>
</section>
@props(['success' => __('Job family created successfully.')])

<form wire:submit="save">
    {{-- Input field for: Job Family Name --}}
    <x-form.boxed-input-text id="dep_name" label="{{ __('Name') }}" name="jobFamilyName"
        :nonce="$nonce" :required="true">
    </x-form.boxed-input-text>
    @error('jobFamilyName')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror

    {{-- Textarea field for: Description --}}
    <x-form.boxed-textarea id="dep_desc" label="{{ __('Description') }}" name="jobFamilyDesc" :nonce="$nonce"
        :rows="6" />
    @error('jobFamilyDesc')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror

    {{-- Submit Button --}}
    <x-buttons.main-btn id="create_dep" label="{{ __('Create Job Family') }}" :nonce="$nonce"
        :disabled="false" class="w-25" :loading="'Creating...'" />

    <span 
        x-data="{ successAlert: false }"
        x-cloak
        x-on:job-family-created.window="successAlert = true; setTimeout(() => { successAlert = false }, 2000)"
        x-show.transition.out.opacity.duration.1500ms="successAlert"
        x-transition:leave.opacity.duration.1500ms
        x-show="successAlert"
        class="fw-bold text-success"
    >
        {{ $success }}
    </span>
</form>
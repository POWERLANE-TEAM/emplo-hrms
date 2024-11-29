@props(['success' => __('Job family created successfully.')])

<form wire:submit="save">

    <div class="row">
        <div class="col-6">
            {{-- Input field for: Job Family Name --}}
            <x-form.boxed-input-text 
                id="dep_name" 
                label="{{ __('Name') }}" 
                name="state.name" 
                :nonce="$nonce"
                :required="true"
                placeholder="{{ __('Enter job family / office name') }}"
            >
            </x-form.boxed-input-text>
            @error('state.name')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-6">
            {{-- Input field for: Office Head --}}
            <x-form.boxed-input-text 
                id="office_head" 
                label="Office Head" 
                :nonce="$nonce" 
                :required="true"
                wire:model.live="query"
                wire:keydown.tab="resetQuery"
                wire:keydown.escape="resetQuery"
                placeholder="Search for employee"
            >
            </x-form.boxed-input-text>

            <div class="dropdown" style="position: relative;">
                @if($employees)
                    <ul class="dropdown-menu show" style="width: 100%; position: absolute; z-index: 1050;">
                        @forelse($employees as $employee)
                            <li wire:key="{{ $employee->id }}">
                                <button type="button" class="dropdown-item"
                                    wire:click="selectEmployee({{ $employee->id }})">
                                    {{ $employee->fullName }}
                                </button>
                            </li>
                            @empty
                                <li class="px-3"> {{ __('No results found.') }} </li>
                        @endforelse
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Textarea field for: Description --}}
    <x-form.boxed-textarea 
        id="dep_desc" label="{{ __('Description') }}" 
        name="state.description" 
        :nonce="$nonce"
        :rows="6" 
        placeholder="{{ __('Enter job family / office description') }}"
    />
    @error('state.description')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
    @enderror

    {{-- Submit Button --}}
    <x-buttons.main-btn wire:target="save" id="create_dep" label="{{ __('Create Job Family') }}" :nonce="$nonce" :disabled="false"
        class="w-25" :loading="'Creating...'" />

    <span x-data="{ successAlert: false }" x-cloak
        x-on:job-family-created.window="successAlert = true; setTimeout(() => { successAlert = false }, 2000)"
        x-show.transition.out.opacity.duration.1500ms="successAlert" x-transition:leave.opacity.duration.1500ms
        x-show="successAlert" class="fw-bold text-success">
        {{ $success }}
    </span>
</form>
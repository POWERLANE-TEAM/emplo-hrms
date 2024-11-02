@props([
    'label' => 'Add Qualification',
    'options' => [], // Pass an array of dropdown options
    'required' => false,
    'id' => 'qualification-input',
    'name' => 'qualification'
])

<div x-data="{ showInput: false }">
    <button 
        x-show="!showInput" 
        @click.prevent="showInput = true" 
        type="button" 
        class="btn btn-primary">
        {{ $label }}
    </button>

    <div x-show="showInput" class="mt-2">
        <label for="{{ $id }}" class="mb-1 fw-semibold">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>

        <div class="input-group mb-3 position-relative">
            <input 
                id="{{ $id }}"
                name="{{ $name }}"
                class="form-control border ps-3 rounded"
                placeholder="Enter qualification" 
                wire:model="{{ $name }}"
                autocomplete="off"
            >

            <!-- Dropdown appended to the input -->
            <select class="form-select rounded" aria-label="Qualification options">
                @foreach ($options as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
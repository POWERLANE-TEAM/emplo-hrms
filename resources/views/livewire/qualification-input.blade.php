<div x-data="{ showInput: false }">
    <!-- Button to show the input field and dropdown -->
    <button x-show="!showInput" @click="showInput = true" type="button"
        class="btn w-100 border-dashed text-primary">
        {{ $label }}
    </button>

    <!-- Input field and dropdown; only shown when showInput is true -->
    <div x-show="showInput" class="mt-2">
        <label for="{{ $id }}" class="mb-1 fw-semibold">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>

        <div class="row align-items-center py-3">
            <!-- Text input field with 7 columns -->
            <div class="col-7">
                <input id="{{ $id }}" name="{{ $name }}" class="form-control border ps-3 rounded"
                    placeholder="Enter qualification" wire:model="{{ $name }}" autocomplete="off">
            </div>

            <!-- Dropdown with 3 columns -->
            <div class="col-3 position-relative">
                <select class="form-control border ps-3 rounded pe-5" aria-label="Qualification options">
                    @foreach ($options as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                <!-- Chevron icon for dropdown -->
                <i data-lucide="chevron-down"
                    class="icon-large position-absolute end-0 top-50 translate-middle-y me-3"></i>
            </div>

            <!-- Buttons with 2 columns (stacked within the same column) -->
            <div class="col-2 d-flex">
                <button type="submit" class="btn btn-success me-2 flex-fill">
                    Go
                </button>
                <button @click.prevent="showInput = false" type="button" class="btn btn-secondary flex-fill">
                    No
                </button>
            </div>
        </div>
    </div>

</div>
{{--
* |--------------------------------------------------------------------------
* | Input Qualification Text Field with Loading
* |--------------------------------------------------------------------------
--}}

<div>
    <div x-data="{ showInput: false }" wire:loading.remove x-cloak>
        <!-- Button to show the input field and dropdown -->
        <button x-show="!showInput" @click="showInput = true" type="button"
            class="btn w-100 border-dashed py-2 text-primary">
            {{ $label }}
        </button>

        <!-- Input field and dropdown; only shown when showInput is true -->
        <div x-show="showInput" class="mt-2">
            <div class="mb-2 mt-3 green-divider"></div>

            <div class="row align-items-center py-3">
                <!-- Text input field -->
                <div class="col-7">
                    <input id="{{ $id }}" name="{{ $name }}" class="form-control border ps-3 rounded"
                        placeholder="Enter qualification..." wire:model="qualificationText" autocomplete="off">
                </div>

                <!-- Dropdown for priority -->
                <div class="col-3 position-relative">
                    <select class="form-select form-control border ps-3 rounded pe-5" wire:model="selectedPriority" aria-label="Qualification options">
                        <option value="">Select Priority</option>
                        @foreach ($options as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    
                </div>

                 <!-- Buttons -->
                <div class="col-2 d-flex">
                    <button type="button" class="btn btn-success me-2 text-white flex-fill" wire:click="addQualification">
                        Go
                    </button>
                    <button @click.prevent="showInput = false" type="button" class="btn btn-secondary flex-fill">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

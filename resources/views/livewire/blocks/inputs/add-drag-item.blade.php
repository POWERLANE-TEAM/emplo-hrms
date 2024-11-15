{{--
* |--------------------------------------------------------------------------
* | Input Normal Drag Item
* |--------------------------------------------------------------------------
--}}

<div>
    <div x-data="{ showInput: false }" wire:loading.remove x-cloak>
        <!-- Button to show the input field and dropdown -->
        <button x-show="!showInput" @click="showInput = true" type="button"
            class="btn w-100 border-dashed py-2 text-primary">
            {{ $label }}
        </button>

        <!-- Input field; only shown when showInput is true -->
        <div x-show="showInput" class="mt-2">
            <div class="mb-2 mt-3 green-divider"></div>

            <div class="row align-items-center py-3">
                <!-- Text input field -->
                <div class="col-10">
                    <input id="{{ $id }}" name="{{ $name }}" class="form-control border ps-3 rounded"
                        placeholder="Type item here..." wire:model="{{ $name }}" autocomplete="off">
                </div>

                <!-- Buttons -->
                <div class="col-2 d-flex">
                    <button type="submit" class="btn btn-success me-2 text-white flex-fill">
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
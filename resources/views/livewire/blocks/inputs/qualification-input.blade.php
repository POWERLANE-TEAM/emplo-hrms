{{--
* |--------------------------------------------------------------------------
* | Input Qualification Text Field with Loading
* |--------------------------------------------------------------------------
--}}

<div>
    <div x-data="{ showInput: false }" x-cloak>
        <!-- Button to show the input field and dropdown -->
        <button x-show="!showInput" @click="showInput = true" type="button"
            class="btn w-100 border-dashed py-2 text-primary">
            {{ __('Add Qualifications') }}
        </button>

        <!-- Input field and dropdown; only shown when showInput is true -->
        <div x-show="showInput" class="mt-2">
            <div class="mb-2 mt-3 green-divider"></div>

            <div class="row align-items-center py-3">
                <!-- Text input field -->
                <div class="col-7">
                    <input id="qualification-input" name="qualification" class="form-control border ps-3 rounded"
                        placeholder="{{ __('Graduate of any 4-year course.') }}" wire:model.blur="qualification" autocomplete="off">

                        @error('qualification')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                </div>

                <!-- Dropdown for priority -->
                <div class="col-3 position-relative">
                    <select class="form-select form-control border ps-3 rounded pe-5" wire:model.blur="priority" aria-label="Qualification options">
                        <option value="">{{ __('Select Priority') }}</option>
                        @foreach ($this->priorityLevels as $priorityLevel => $index)
                            <option value="{{ $index }}">{{ $priorityLevel }}</option>
                        @endforeach
                    </select>
                    @error('priority')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                 <!-- Buttons -->
                <div class="col-2 d-flex">
                    <button type="button" class="btn btn-success me-2 text-white flex-fill" wire:loading.attr="disabled" wire:click="save">
                        {{ __('Go') }}
                    </button>
                    <button @click.prevent="showInput = false" wire:loading.attr="disabled" type="button" class="btn btn-secondary flex-fill">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<section>
    <section class="col-md-12 d-flex">
        <div class="w-100">
            <div>
                <div class="row px-3 mb-3">
                    <div wire:ignore class=" col-6 d-flex align-items-center fw-bold">
                        <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>Performance Category
                    </div>
                    {{-- Performance Periods --}}
                    @foreach ($this->periods as $period)
                        <div wire:key="{{ $period->id }}" class="col-2">
                            <div class="text-center text-muted fw-bold">
                                {{ $period->name }}
                            </div>
                        </div>
                    @endforeach
                </div>

                @foreach($this->categories as $category)
                    <div class="card p-4 mb-4 d-flex">
                        <div wire:key="{{ $category->id }}" class="row px-3">
                            <div class="col-5">
                                <p class="fw-bold fs-5 text-primary">{{ $loop->iteration }}. {{ $category->name }}</p>
                                <p>{{ $category->description }}</p>
                            </div>

                            <div class="col-1 d-flex justify-content-center">
                                <div class="vertical-line"></div>
                            </div>

                            <div class="col-6 d-flex justify-content-around">
                                @foreach ($this->periods as $period)
                                    <div class="col-3 px-2 d-flex align-items-center">
                                        <x-form.boxed-dropdown 
                                            id="{{ $period->id }}_score_{{ $loop->iteration }}"
                                            wire:model.blur="ratings.{{ $category->id }}.{{ $period->id }}"
                                            :nonce="$nonce"
                                            :options="$this->ratingScales"
                                            placeholder="Select score"
                                        >
                                        </x-form.boxed-dropdown>
                                    </div>
                                @endforeach                            
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="col-md-12 d-flex">
        <div class="w-100">
            <div class="row mb-4">
                <div class="col-6">
                    <div class="card bg-body-secondary border-0">
                        <div class="p-4 align-items-center text-center">
                            <div class="fw-bold fs-3 text-primary">{{ $finalRating ?? __('0.00') }}</div>
                            <div class="fw-medium fs-6 pt-1">{{ __('Final Rating') }}</div>
                        </div>
                    </div>
                </div>
        
                <div class="col-6">
                    <div class="card bg-body-secondary border-0">
                        <div class="p-4 align-items-center text-center">
                            <div class="fw-bold fs-3 text-primary">{{ $performanceScale ?? __('Not finalized') }}</div>
                            <div class="fw-medium fs-6 pt-1">{{ __('Performance Scale') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </section>

    <div>
        <x-form.boxed-textarea 
            id="announcement_desc" 
            label="Comments" 
            name="comments" 
            :nonce="$nonce"
            :rows="6" 
            :required="false" 
            placeholder="{{ __('Write here further remarks how you feel about the evaluatee being assessed.') }}" 
        />
    </div>

    <section class="col-md-12">
        <div class="row">
            <!-- Note -->
            <div class="col-5 ps-3">
                <x-info_panels.note
                    note="{{ __('This form requires your signature. By clicking submit, your signature will be automatically added to the downloadable file.') }}" />
            </div>
            <!-- Button -->
            <div class="col-7 d-flex align-items-center text-end">
                <x-buttons.main-btn label="Submit Evaluation" wire:click.prevent="save" :nonce="$nonce"
                    :disabled="false" class="w-50" :loading="'Submitting...'" />
            </div>
        </div>
    </section>
</section>

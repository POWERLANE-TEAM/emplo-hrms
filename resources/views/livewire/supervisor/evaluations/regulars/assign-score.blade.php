<div>
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($this->routePrefix.'.performances.probationaries.index')"> 
                {{ __('Performance Evaluations') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($routePrefix . '.performances.probationaries.create')">
                {{ __('Assign Score') }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>

    <section class="row">
        <div class="col-6">
            <x-headings.header-with-status title="{{ $employee->full_name }}" color="info" badge="{{ $employee->status->emp_status_name }}">
                <span class="fw-bold">{{ __('Job Title') }}: </span>
                {{ $employee->jobTitle->job_title }}
                </x-profile-header>
        </div>
        <div class="col-6 pt-2">
            <x-info_panels.callout type="info" :description="__('Learn more about the <a href=\'/information-centre?section=evaluation-policy\' class=\'text-link-blue\'>scoring evaluation</a> metrics and details.')">
            </x-info_panels.callout>

        </div>
    </section>

    <section class="mb-5 mt-3">
        <div class="d-flex mb-5 row align-items-stretch">
            <section>
                <section class="col-md-12 d-flex">
                    <div class="w-100">
                        <div>
                            <div class="row px-3 mb-3">
                                <div wire:ignore class=" col-8 d-flex align-items-center fw-bold">
                                    <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>
                                    {{ __('Performance Category') }}
                                </div>

                                <div class="col-4">
                                    <div class="text-center text-muted fw-bold">
                                        {{ __('Annual') }}
                                    </div>
                                </div>
                            </div>

                            @foreach($this->categories as $category)
                                <div class="card p-4 mb-4 d-flex">
                                    <div wire:key="{{ $category->id }}" class="row">
                                        <div class="col-5">
                                            <p class="fw-bold fs-5 text-primary">{{ $loop->iteration }}. {{ $category->name }}</p>
                                            <p>{{ $category->description }}</p>
                                        </div>

                                        <div class="col-1 d-flex justify-content-center">
                                            <div class="vertical-line"></div>
                                        </div>

                                        <div class="col-4 d-flex justify-content-start">
                                            <div class="col-3 d-flex align-items-center">
                                                @foreach ($this->ratingScales as $rating)
                                                    <div wire:key="{{ $rating->id }}" class="form-check form-check-inline">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="radio" 
                                                            name="ratings.{{ $category->id}}"
                                                            wire:model="ratings.{{ $category->id }}"
                                                            id="score.{{ $category->id . $rating->id }}" 
                                                            value="{{ $rating->id }}"
                                                            required
                                                            data-rating="{{ $rating->id }}" 
                                                            data-scale="{{ $rating->scale }}"
                                                            onclick="calculateAverage()"
                                                        />
                                                        <label 
                                                            class="form-check-label" 
                                                            for="score.{{ $category->id . $rating->id }}"
                                                        >{{ "{$rating->scale} -  {$rating->name}" }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>                          
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
                                        <div class="fw-bold fs-3 text-primary"><span id="averageValue">0.00</span></div>
                                        <div class="fw-medium fs-6 pt-1">{{ __('Final Rating') }}</div>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-6">
                                <div class="card bg-body-secondary border-0">
                                    <div class="p-4 align-items-center text-center">
                                        <div class="fw-bold fs-3 text-primary"><span id="performanceScale">{{ __('Not Finalized') }}</span></div>
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
                    <!-- Button -->
                    <div class="d-flex align-items-center text-end">
                        <x-buttons.main-btn label="Submit Evaluation" wire:click.prevent="save" :nonce="$nonce"
                            :disabled="false" class="w-50" :loading="'Submitting...'" />
                    </div>
                </section>
            </section>
        </div>
    </section>
</div>

{{-- @script --}}
<script>
    const calculateAverage = () => {
        let totalRating = 0;
        let totalVotes = 0;

        const selectedRatings = document.querySelectorAll('input[type="radio"]:checked');

        selectedRatings.forEach(rating => {
            totalRating += parseFloat(rating.value);
            totalVotes++;
        });

        const average = totalVotes > 0 ? (totalRating / totalVotes).toFixed(2) : 0;
        document.getElementById('averageValue').textContent = average;

        let performanceScale = '';
        if (average >= 1 && average < 2) {
            performanceScale = 'Needs Improvement';
        } else if (average >= 2 && average < 3) {
            performanceScale = 'Meets Expectation';
        } else if (average >= 3 && average < 4) {
            performanceScale = 'Exceeds Expectation';
        } else if (average >= 4 && average <= 5) {
            performanceScale = 'Outstanding';
        }

        document.getElementById('performanceScale').textContent = performanceScale;
    }
</script>
{{-- @endscript --}}
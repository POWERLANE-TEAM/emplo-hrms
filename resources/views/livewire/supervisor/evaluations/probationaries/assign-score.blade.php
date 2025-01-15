@use(App\Enums\PerformanceEvaluationPeriod)

@php
    // dd($this->currentPeriod);
    $isThirdMonth = $this->currentPeriod->period_name === PerformanceEvaluationPeriod::THIRD_MONTH->value;
    $isFifthMonth = $this->currentPeriod->period_name === PerformanceEvaluationPeriod::FIFTH_MONTH->value;
    $isFinalMonth = $this->currentPeriod->period_name === PerformanceEvaluationPeriod::FINAL_MONTH->value;
@endphp

<section>
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
            <x-headings.header-with-status title="{!! $employee->full_name !!}" color="info" badge="{{ $employee->status->emp_status_name }}">
                <span class="fw-bold">{{ __('Job Title') }}: </span>
                {{ $employee->jobTitle->job_title }}
                </x-profile-header>
        </div>
        <div class="col-6 pt-2">
            <x-info_panels.callout type="info" :description="__('Learn more about the <a href=\'/information-centre?section=evaluation-policy\' class=\'text-link-blue\'>scoring evaluation</a> metrics and details.')">
            </x-info_panels.callout>

        </div>
    </section>
    
    <section class="col-md-12">
        <div class="w-100">
            <div>
                <div class="row px-3 mb-3 align-items-center">
                    <div wire:ignore class="col-6 fw-bold d-flex align-items-center">
                        <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>
                        {{ __('Performance Category') }}
                    </div>
    
                    @foreach ($this->periods as $key => $period)
                        <div class="col-2 d-flex justify-content-center">
                            <span class="text-muted fw-bold">
                                {{ $period->getLabel() }}
                            </span>
                        </div>
                    @endforeach
                </div>
    
                @foreach($this->categories as $category)
                    <div class="card p-4 mb-4">
                        <div wire:key="{{ $category->id }}" class="row align-items-center">
                            <div class="col-5">
                                <p class="fw-bold fs-5 text-primary">{{ $loop->iteration }}. {{ $category->name }}</p>
                                <p>{{ $category->description }}</p>
                            </div>
    
                            <div class="col-1 d-flex justify-content-center">
                                <div class="vertical-line"></div>
                            </div>
    
                            <div class="col-6">
                                <div class="row">
                                    @if ($this->thirdMonthEvaluation)
                                        @foreach ($this->thirdMonthEvaluation as $evaluation)
                                            @if ($evaluation->category === $category->id)
                                                <div class="col-4 px-2 justify-content-center d-flex align-items-center">
                                                    <div class="fw-bold text-secondary-emphasis">
                                                        {{ "{$evaluation->ratingScale} - {$evaluation->ratingName}" }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-4 px-2 text-center d-flex align-items-center">
                                            <x-form.boxed-dropdown 
                                                id="score{{ $category->id }}"
                                                wire:model="ratings.{{ $category->id }}"
                                                :nonce="$nonce"
                                                :options="$isThirdMonth ? $this->ratingScales : []"
                                                placeholder="{{ $isThirdMonth ? __('Select Score') : __('Disabled') }}"
                                                :disabled="! $isThirdMonth"
                                            ></x-form.boxed-dropdown>                                      
                                        </div>                                    
                                    @endif
                                    
                                    @if ($this->fifthMonthEvaluation)
                                        @foreach ($this->fifthMonthEvaluation as $evaluation)
                                            @if ($evaluation->category === $category->id)
                                                <div class="col-4 px-2 justify-content-center d-flex align-items-center">
                                                    <div class="fw-bold text-secondary-emphasis">
                                                        {{ "{$evaluation->ratingScale} - {$evaluation->ratingName}" }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-4 px-2 text-center d-flex align-items-center">
                                            <x-form.boxed-dropdown 
                                                id="score{{ $category->id }}"
                                                wire:model="ratings.{{ $category->id }}"
                                                :nonce="$nonce"
                                                :options="$isFifthMonth ? $this->ratingScales : []"
                                                placeholder="{{ $isFifthMonth ? __('Select Score') : __('Disabled') }}"
                                                :disabled="! $isFifthMonth"
                                            ></x-form.boxed-dropdown>                                      
                                        </div>                                    
                                    @endif
                                    
                                    <div class="col-4 text-start">
                                        <x-form.boxed-dropdown 
                                            id="score{{ $category->id }}"
                                            wire:model="ratings.{{ $category->id }}"
                                            :nonce="$nonce"
                                            :options="$isFinalMonth ? $this->ratingScales : []"
                                            placeholder="{{ $isFinalMonth ? __('Select Score') : __('Disabled') }}"
                                            :disabled="! $isFinalMonth"
                                        ></x-form.boxed-dropdown>                                           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <x-info_panels.callout type="info" :description="__('Final rating and performance scale will be automatically compute and interpreted upon finalization of final month, together with recommendation of probationary to become regular employee.')"></x-info_panels.callout>

    <section class="col-md-12 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <x-form.checkbox 
                            container_class="pb-2" 
                            :nonce="$nonce" 
                            id="recommended-toggle-true"
                            name="recommendedToRegular" 
                            value="true" 
                            :disabled="! $isFinalMonth"
                            class="checkbox checkbox-primary">
                        
                            <x-slot:label>
                                @php $greenText = '<span class="text-primary fw-semibold">recommend</span>' @endphp

                                {!! __("I {$greenText} the probationary employee become a regular employee") !!}
                            </x-slot:label>
                        </x-form.checkbox>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <x-form.checkbox
                            container_class="" 
                            :nonce="$nonce" 
                            id="recommended-toggle-false"
                            name="recommendedToRegular.false" 
                            value="false" 
                            :disabled="! $isFinalMonth"
                            class="checkbox checkbox-primary">

                            <x-slot:label>
                                @php $dangerText = '<span class="text-danger fw-semibold">do not recommend</span>' @endphp

                                {!! __("I {$dangerText} the probationary employee become a regular employee") !!}
                            </x-slot:label>
                        </x-form.checkbox>                 
                    </div>
                </div>
            </div>
        </div>

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
        <div class="d-flex align-items-center text-end">
            <x-buttons.main-btn label="Submit Evaluation" wire:click.prevent="save" :nonce="$nonce"
                :disabled="false" class="w-50" :loading="'Submitting...'" />
        </div>
    </section>
</section>

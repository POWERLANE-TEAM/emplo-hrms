@use(App\Enums\PerformanceEvaluationPeriod)

<section>
    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="route($this->routePrefix.'.performances.probationary')">
                {{ __('Performance Evaluations (Probationary)') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($this->routePrefix.'.performances.probationary.show')">
                {{ __('Your Performance') }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>
    
    <div class="row d-flex align-items-center">
        <div class="col">
            <x-info_panels.callout 
                type="{{ $performance->isAcknowledged ? 'success' : 'info' }}" 
                :description="$performance->isAcknowledged ? 
                    __('Mark as received at '.$performance?->signedAt) 
                    : __('Acknowledging this evaluation form will serve as your signature and approval.')">
            </x-info_panels.callout>
        </div>
    </div>
    
    <section class="mb-5 mt-3">
        <div class="d-flex mb-5 row align-items-stretch">
            <section class="col-md-5 d-flex">
                <div class="w-100">

                    <!-- Navigation Tabs -->
                    <div class="p-2">
                        @include('components.includes.tab_navs.eval-result-navs')
                    </div>
                    
                    <div class="card border-primary mt-1 px-5 py-4 w-100">

                        <!-- Overview Tab Section-->
                        <section id="overview" class="tab-section">

                            <!-- Section Title -->
                            <div class="text-primary fs-3 fw-bold text-center">
                                {{ __('Overview') }}
                            </div>

                            <div class="text-center pt-3 pb-4">
                                <p class="fw-bold fs-4">
                                    @php $finalRating = session()->get('final_rating'); @endphp
                                    
                                    {{ __("{$finalRating['format']} - {$finalRating['scale']}") }}
                                </p>
                                <p class="text-muted">{{ __('Final Rating & Performance Scale') }}</p>
                            </div>

                            @php
                                $icon = $isRecommendedRegular ? 'badge-check' : 'badge-alert';
                                $textColor = $isRecommendedRegular ? 'text-success' : 'text-danger';
                                $text = $isRecommendedRegular 
                                    ? __('Recommended to become a regular employee.') 
                                    : __('Do not recommend to become a regular employee.');
                            @endphp

                            <div class="pb-3">
                                <p class="fw-medium fs-5">{{ __('Supervisor’s Final Recommendation') }}</p>

                                <div class="row py-3 d-flex align-items-center {{ $textColor }}">
                                    <div class="col-2 d-flex justify-content-end p-0">
                                        <i data-lucide="{{ $icon }}" class="icon icon-xlarge"></i>
                                    </div>
                                    <div class="col justify-content-start">
                                        <span class="fs-5">{{ $text }} </span>
                                    </div>
                                </div>
                            </div>                            

                            <!-- SECTION: Main Approvals -->
                            <div class="pb-3">
                                <p class="fw-medium fs-5">{{ __('Main Approvals') }}</p>
                                <div class="ps-4 pe-2 py-3">
                                    <x-form.checkbox container_class="" :nonce="$nonce" id="supervisor_approval" name="supervisor_approval"
                                        class="checkbox checkbox-primary" disabled="true" checked>

                                        <x-slot:label>
                                            <div class="d-flex flex-column">
                                                <div class="fs-5">{{ $performance->evaluator }}</div>
                                                <div class="text-primary">{{ $performance->evaluatorJobTitle }}</div>
                                            </div>
                                        </x-slot:label>
                                    </x-form.checkbox>
                                </div>

                                <!-- Head Department Approval -->
                                <div class="ps-4 pe-2 py-2">
                                    <div class="row">
                                        <div class="col-9">
                                            <x-form.checkbox container_class="" :nonce="$nonce" id="head_dept_approval" name="head_dept_approval"
                                                class="checkbox checkbox-primary" disabled :checked="$performance->secondaryApproverSignedAt">

                                                <x-slot:label>
                                                    <div class="d-flex flex-column">
                                                        <div class="fs-5">
                                                            {{ $performance->secondaryApprover ?? 
                                                                $employee->jobTitle->jobFamily->head->full_name ??
                                                                __('Awaiting Approval')
                                                            }}
                                                        </div>
                                                        <div class="text-primary">
                                                            {{ $performance->secondaryApproverJobTitle ?? 
                                                                $employee->jobTitle->jobFamily?->head?->jobTitle?->job_title
                                                            }}
                                                        </div>
                                                    </div>
                                                </x-slot:label>
                                            </x-form.checkbox>                                        
                                        </div>
                                        @if (is_null($performance->secondaryApproverSignedAt))
                                            <div class="col-3">
                                                <x-status-badge color="info">Pending</x-status-badge>
                                            </div>
                                        @endif   
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: HR Approvals -->
                            <div class="pb-3">
                                <p class="fw-medium fs-5">{{ __('Human Resources Department') }}</p>

                                <!-- Supervisor Approval -->
                                <div class="ps-4 pe-2 py-3">
                                    <div class="row">
                                        <div class="col-9">
                                            <x-form.checkbox container_class="" :nonce="$nonce" id="hr_staff_approval" name="hr_staff_approval"
                                                class="checkbox checkbox-primary" disabled :checked="$performance->thirdApproverSignedAt">

                                                <x-slot:label>
                                                    <div class="d-flex flex-column">
                                                        <div class="fs-5">
                                                            {{ $performance->thirdApprover ?? $this->randomHrdStaff }}
                                                        </div>
                                                        <div class="text-primary">
                                                            {{ $performance->thirdApproverJobTitle ?? __('HR Staff') }}
                                                        </div>
                                                    </div>
                                                </x-slot:label>
                                            </x-form.checkbox>                                        
                                        </div>
                                        @if (is_null($performance->thirdApproverSignedAt))
                                            <div class="col-3">
                                                <x-status-badge color="info">Pending</x-status-badge>
                                            </div>
                                        @endif      
                                    </div>
                                </div>

                                <!-- Head Department Approval -->
                                <div class="ps-4 pe-2 py-2">
                                    <div class="row">
                                        <div class="col-9">
                                            <x-form.checkbox container_class="" :nonce="$nonce" id="hr_head_approval"
                                                name="hr_head_approval" class="checkbox checkbox-primary" disabled :checked="$performance->fourthApproverSignedAt">

                                                <x-slot:label>
                                                    <div class="d-flex flex-column">
                                                        <div class="fs-5">
                                                            {{ $performance->fourthApprover ?? $this->hrdManager }}
                                                        </div>
                                                        <div class="text-primary">
                                                            {{ $performance->fourthApproverJobTitle ?? __('HRD Manager') }}
                                                        </div>
                                                    </div>
                                                </x-slot:label>
                                            </x-form.checkbox>
                                        </div>
                                        @if (is_null($performance->fourthApproverSignedAt))
                                            <div class="col-3">
                                                <x-status-badge color="info">Pending</x-status-badge>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="ps-4 pe-2 py-2">

                                </div>
                            </div>
                        </section>
                        
                        <section id="comments" class="tab-section">
                            @foreach ($employee->performancesAsProbationary as $evaluation)
                                <div class="d-flex justify-content-between">
                                    <div class="me-auto d-flex align-items-start">
                                        @if (! $evaluation->details->isNotEmpty())
                                            <div class="text-muted">
                                                {{ __('No evaluation for this period') }}
                                            </div>
                                        @endif
                                        @foreach ($evaluation->details as $detail)
                                            {{ $detail->evaluator_comments ?? __('No further comments.') }}
                                        @endforeach
                                    </div>
                                    <div class="ms-auto">
                                        @php 
                                            $period = PerformanceEvaluationPeriod::from($evaluation->period_name); 
                                        @endphp

                                        <x-status-badge 
                                            color="{{ $period->value === PerformanceEvaluationPeriod::FINAL_MONTH->value ? 'success' : 'info' }}"
                                        >{{ $period->getLabel() }}
                                        </x-status-badge>
                                    </div>             
                                </div>
                                <hr>  
                            @endforeach
                        </section>
                    </div>
                </div>
            </section>

            <section class="col-md-7 d-flex">
                <div class="w-100">
                    <livewire:employee.performances.probationary.performance-category-ratings :$yearPeriod :$employee />
                </div>
            </section>
        </div>

        <div class="row d-flex justify-content-start">
            <div class="col">
                <x-form.boxed-textarea
                    name="comments"
                    id="comments" 
                    label="{{ __('Comments') }}" 
                    :nonce="$nonce"
                    :rows="6" 
                    description="{{ ! $performance->isAcknowledged ? __('Feel free to write any comments on how you feel about this performance evaluation.') : null }}"
                    :readonly="$performance->isAcknowledged"
                    placeholder="{{ $performance?->comments }}"
                ></x-form.boxed-textarea>
                @error('comments')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror

                {{-- Buttons --}}
                @if (! $performance->isAcknowledged)
                    <div class="">
                        <button
                            wire:click="markAsAcknowledged"
                            type="submit" 
                            name="submit"
                            class="btn btn-primary btn-lg fw-regular col-6 w-25"
                            >{{ __('Mark As Acknowledged') }}
                        </button>                
                    </div>
                @endif
            </div>            
        </div>
    </section>    
</section>
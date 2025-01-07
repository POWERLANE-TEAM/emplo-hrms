@use(App\Enums\PerformanceEvaluationPeriod)

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

                        {{-- <div>
                            <button onclick="openModal('approvalHistoryModal')" class="btn fw-medium underline hover-opacity underline">Approval History</button>
                        </div> --}}
                    </section>
                    
                    <section id="comments" class="tab-section">
                        @foreach ($employee->performancesAsProbationary as $evaluation)
                            <div class="d-flex justify-content-between">
                                <div class="me-auto d-flex align-items-start">
                                    @foreach ($evaluation->details as $detail)
                                        {{ $detail->evaluator_comments ?? __('None provided.') }}
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
                <livewire:hr-manager.evaluations.probationaries.performance-category-ratings :$yearPeriod :$employee />
            </div>
        </section>
    </div>

    {{-- Buttons --}}
    @if (! $performance->thirdApproverSignedAt || ! $performance->fourthApproverSignedAt)
        <div class="col-5 ps-3 pe-4">
            <button
                wire:click="markAsReceived"
                type="submit" 
                name="submit"
                class="btn btn-primary btn-lg fw-regular col-6 w-100"
                >{{ __('Mark As Received') }}
            </button>
        </div>
    @endif

</section>
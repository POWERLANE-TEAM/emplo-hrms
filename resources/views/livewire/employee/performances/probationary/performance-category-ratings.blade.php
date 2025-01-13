@use(App\Enums\PerformanceEvaluationPeriod)

<div>
    <div class="row px-3 mb-4">

        <div class=" col-8 d-flex align-items-center fw-bold">
            <i class="icon p-1 mx-2 text-primary" data-lucide="baseline"></i>
            {{ __('Performance Category') }}
        </div>

        @foreach ($this->periods as $period)
            @if ($loop->last)
                <div class="col-1 d-flex align-items-center">
                    <div class="text-center fw-bold text-primary justify-content-center">
                        {{ $period->getShorterLabel() }}
                    </div>                
                </div>
            @else
                <div class="col-1 px-2 mr-3">
                    <div class="text-center fw-bold">{{ $period->getShorterLabel() }}</div>
                    <div class="text-center text-muted fs-7">mos</div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="scrollable-container visible-gray-scrollbar">
        @foreach($previews as $key => $preview)
            <div class="card p-4 mb-4 d-flex">
                <div class="row px-3">
                    <div class="col-7">
                        <p class="fw-bold fs-5 text-primary">
                            {{ "{$loop->iteration}. {$key}" }}
                        </p>
                        <p>{{ $preview->categoryDesc }}</p>
                    </div>
                
                    <div class="col-2 d-flex justify-content-center">
                        <div class="vertical-line"></div>
                    </div>
    
                    @php
                        $third = PerformanceEvaluationPeriod::THIRD_MONTH->value;
                        $fifth = PerformanceEvaluationPeriod::FIFTH_MONTH->value;
                        $final = PerformanceEvaluationPeriod::FINAL_MONTH->value;
                    @endphp
    
                    <div class="col-3 d-flex justify-content-between">
                        <div class="col-1 px-2 d-flex align-items-center">
                            <div class="fw-bold text-center text-muted">
                                {{ $preview->scores->has($third) ? $preview->scores[$third] : 0 }}
                            </div>                           
                        </div>
                        
                        <div class="col-1 px-2 d-flex align-items-center">
                            <div class="fw-bold text-center text-muted">
                                {{ $preview->scores->has($fifth) ? $preview->scores[$fifth] : 0 }}
                            </div>                           
                        </div>
    
                        <div class="col-1 px-2 d-flex align-items-center">
                            <div class="fw-bold text-center text-muted">
                                {{ $preview->scores->has($final) ? $preview->scores[$final] : 0 }}
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
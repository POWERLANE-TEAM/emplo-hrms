@props(['pipData' => null, 'performance' => null, 'pip' => null])
@use ('App\Http\Helpers\Timezone')

<x-headings.main-heading :isHeading="true">
    <x-slot:heading>
        {{ __('Performance Plan Generator') }}
    </x-slot:heading>

    <x-slot:description>
        <p>{{ __('Creates customized development plan by analyzing performance evaluation results.') }}
        </p>
    </x-slot:description>
</x-headings.main-heading>

@php
    if(!$performance){
        $performance = $pip->regularPerformance;

        // dd($pipData);
    }

    if($pip){
        $pipData = $pip->details;
        // fix bug that when the string is from database it is only one line
        // as newline in string is not a new line character
        $pipData = str_replace('\n', "\n", $pipData);
    }
@endphp

<div class="ms-n3 mt-n4 mb-5">
    <x-headings.sparkle-callout>
        <x-slot:description>
            Generated a performance plan addressing <span> {{ $performance->employeeEvaluatee->fullname }}'s</span> <a
                href="{{ route($routePrefix . '.performances.regulars.review', ['performance' => $performance->regular_performance_id]) }}">{{ \Carbon\Carbon::parse($performance->period->start_date)->setTimezone(Timezone::get())->format('Y') }}
                {{ __('evaluation') }}</a> . Check them out below.
        </x-slot:description>
    </x-headings.sparkle-callout>
</div>

<div class="card border border-primary px-5 py-4">
    <x-markdown>
        {!! nl2br(e($pipData)) !!}
    </x-markdown>
</div>

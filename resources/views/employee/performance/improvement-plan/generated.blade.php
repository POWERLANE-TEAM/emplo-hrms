@extends('components.layout.employee.layout', ['description' => 'Employee Dashboard', 'nonce' => $nonce])
@use ('Illuminate\View\ComponentAttributeBag')
@use ('App\Http\Helpers\Timezone')

@section('head')
    <title>PIP Central Hub</title>
    <script rel="preload" as="script" type="text/js" src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <script src="https://unpkg.com/lucide@0.428.0/dist/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@pushOnce('pre-scripts')
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/employee/hr-manager/pip.js'])
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/hr-manager/evaluator.css'])
@endPushOnce
@section('content')

{{-- {{dd($performance)}} --}}
    <x-headings.main-heading :isHeading="true">
        <x-slot:heading>
            {{ __('Performance Plan Generator') }}
        </x-slot:heading>

        <x-slot:description>
            <p>{{ __('Creates customized development plan by analyzing performance evaluation results.') }}
            </p>
        </x-slot:description>
    </x-headings.main-heading>

    <div class="ms-n3 mt-n4 mb-5">
        <x-headings.sparkle-callout>
            <x-slot:description>
                Generated a performance plan addressing <span> {{$performance->employeeEvaluatee->fullname}}'s</span> <a href="{{route($routePrefix .'.performances.regulars.review', ['performance'=> $performance->regular_performance_id])}}">{{ \Carbon\Carbon::parse($performance->period->start_date)->setTimezone(Timezone::get())->format('Y')}} {{__('evaluation')}}</a> . Check them out below.
            </x-slot:description>
        </x-headings.sparkle-callout>
    </div>

<div class="card border border-primary px-5 py-4">
    <x-markdown>
        {!! nl2br(e($data)) !!}
    </x-markdown>
</div>

<div class="col-2 mt-4 px-2">

    <button type="button" class="btn btn-primary btn-lg w-100 ">Save</button>
</div>

@endsection

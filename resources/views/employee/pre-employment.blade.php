@extends('components.layout.applicant.layout', ['description' => 'Guest Layout', 'nonce' => $nonce])

@section('head')
<title>Submit Requirements</title>
@endsection

@pushOnce('pre-scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
@endPushOnce

@pushOnce('scripts')
    @vite(['resources/js/applicant/pre-employment.js'])
@endPushOnce

@pushOnce('pre-styles')
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endPushOnce

@pushOnce('styles')
    @vite(['resources/css/employee/pre-employment.css'])
@endPushOnce


@section('content')
<div class="margin-right-n-book">
    <nav aria-label="breadcrumb" class="mb-3 ms-n4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/application/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pre-employment Requirements</li>
        </ol>
    </nav>

    <hgroup class="mb-5">
        <h1 class="fw-bold">Pre-Employment Requirements</h1>
        <div>
            <b id="h-pre-employment-status">Status: </b> <span aria-labelledby="h-pre-employment-status"> in
                Progress of
                Submission</span>
        </div>
    </hgroup>

    @livewire('employee.pre-employment')
</div>
@endsection

@section('after-main')
<td colspan="4" class=" d-flex">

</td>
@endsection

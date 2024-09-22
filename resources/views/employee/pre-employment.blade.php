@php
    $nonce = csp_nonce();
@endphp

{{-- {{ dd(request()) }}e --}}

@extends('layout.applicant', ['description' => 'Guest Layout', 'nonce' => $nonce, 'main_content_class' => 'container'])

@section('head')
    <title>Applicant</title>
    <script src="{{ Vite::asset('resources/js/forms/nbp.min.js') }}" defer></script>
    @vite(['resources/js/employee/pre-employment.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['vendor/node_modules/jquery/dist/jquery.slim.min.js'])

    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
@endsection


@section('content')
    <div class="margin-right-n-book">
        <nav aria-label="breadcrumb" class="mb-3 ms-n4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pre-employment Requirements</li>
            </ol>
        </nav>

        <hgroup class="mb-5">
            <h1 class="fw-bold">Pre-Employment Requirements</h1>
            <div>
                <b id="h-pre-employment-status">Status: </b> <span aria-labelledby="h-pre-employment-status"> in Progress of
                    Submission</span>
            </div>
        </hgroup>

        @livewire('employee.pre-employment')

    </div>
@endsection

<td colspan="4" class=" d-flex">

</td>

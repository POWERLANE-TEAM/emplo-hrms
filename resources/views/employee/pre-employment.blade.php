@php
    $nonce = csp_nonce();
@endphp

@extends('layout.applicant', ['description' => 'Guest Layout', 'nonce' => $nonce, 'main_content_class' => 'container'])

@section('head')
    <title>Applicant</title>
    <script src="build/assets/nbp.min.js" defer></script>
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

        {{-- <div class="bg-body-secondary d-flex flex-md-nowrap border-0 rounded-3 px-5 py-4">
            <span class="fw-bold fs-5">
                <span class=" text-primary">
                    11
                    <span>requirements</span>
                </span>
                <span>
                    out of
                    <span>
                        18
                    </span>
                    submitted
                </span>
            </span>

            <div class="ms-md-auto">
                <button class=" bg-transparent border-0"><i class="icon p-1 d-inline text-info"
                        data-lucide="arrow-down-wide-narrow"></i>Sort By</button>
                <button class="bg-transparent border-0"><i class="icon p-1 d-inline text-info"
                        data-lucide="list-filter"></i>Filters</button>
            </div>
            <div class="dropdown px-2 d-inline-block">
                <button class=" bg-transparent border-0 dropdown-toggle d-flex align-content-center"
                    data-bs-toggle="dropdown">
                    <i class="icon p-1 d-inline text-info" data-lucide="download"></i>
                    Download as
                </button>
                <ul class="dropdown-menu">
                    <li class="dropdown-item">PDF</li>

                </ul>
            </div>
        </div>

        <table class="table table-hover table-borderless text-center" aria-label="Pre-employment documents"
            aria-description="List of Pre-employment documents to be submitted.">
            <thead>
                <tr>
                    <th class="col-5"><i class="icon p-1 d-inline text-primary" data-lucide="file-text"></i>Requirement
                    </th>
                    <th class="col-1"><i class="icon p-1 d-inline text-primary" data-lucide="check-circle"></i>Status</th>
                    <th class="col-3"><i class="icon p-1 d-inline text-primary" data-lucide="paperclip"></i>Attachment</th>
                    <th class="col-3"><i class="icon p-1 d-inline text-primary" data-lucide="upload"></i>Upload</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                    <td class="">
                        <div class="fw-bold">Document</div>
                    </td>
                    <td>
                        <x-status-badge color="danger">Invalid</x-status-badge>
                    </td>
                    <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                            Attachment</button></td>
                    <td><button class="btn btn-primary"> <i class="icon p-1  d-inline" data-lucide="plus-circle"></i>
                            Upload</button></td>
                </tr>
                <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                    <td class="">
                        <div class="fw-bold">Document</div>
                    </td>
                    <td>
                        <x-status-badge color="danger">Invalid</x-status-badge>
                    </td>
                    <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                            Attachment</button></td>
                    <td><button class="btn btn-primary"> <i class="icon p-1  d-inline" data-lucide="plus-circle"></i>
                            Upload</button></td>
                </tr>
            </tbody>

            <tbody class="text-start">
                <tr class="no-hover">
                    <td colspan="4">
                        <section class="d-flex  px-4">
                            <div class="text-primary text-wrap ">For diplomas(s), please select
                                attainment:
                            </div>
                            <div class=" ms-auto d-flex gap-4 text-nowrap align-items-center">
                                <div class="position-relative d-flex">
                                    <input type="checkbox" id="cbox-diploma-highschool" name=""
                                        class="checkbox checkbox-primary">
                                    <label for="cbox-diploma-highschool" class="checkbox-label">JR./SR./HS</label>
                                </div>
                                <div class="position-relative d-inline-flex">
                                    <input type="checkbox" id="cbox-diploma-undergrad" name=""
                                        class="checkbox checkbox-primary">
                                    <label for="cbox-diploma-undergrad" class="checkbox-label">College Undergrad</label>
                                </div>
                                <div class="position-relative d-inline-flex">

                                    <input type="checkbox" id="cbox-diploma-voc-grad" name=""
                                        class="checkbox checkbox-primary">
                                    <label for="cbox-diploma-voc-grad" class="checkbox-label">Vocational Grad</label>
                                </div>
                                <div class="position-relative d-inline-flex">
                                    <input type="checkbox" id="cbox-diploma-graduate" name=""
                                        class="checkbox checkbox-primary">
                                    <label for="cbox-diploma-graduate" class="checkbox-label">College Graduate</label>
                                </div>
                            </div>
                        </section>
                    </td>
                </tr>
            </tbody>

            <tbody>
                <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                    <td class="">
                        <div class="fw-bold">Document</div>
                    </td>
                    <td>
                        <x-status-badge color="danger">Invalid</x-status-badge>
                    </td>
                    <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                            Attachment</button></td>
                    <td><button class="btn btn-primary">Upload</button></td>
                </tr>
                <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                    <td class="">
                        <div class="fw-bold">Document</div>
                    </td>
                    <td>
                        <x-status-badge color="danger">Invalid</x-status-badge>
                    </td>
                    <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                            Attachment</button></td>
                    <td><button class="btn btn-primary">Upload</button></td>
                </tr>
            </tbody>

        </table> --}}

        @livewire('employee.pre-employment')

    </div>
@endsection

<td colspan="4" class=" d-flex">

</td>

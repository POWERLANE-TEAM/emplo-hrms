<section>
    <div class="bg-body-secondary d-flex flex-md-nowrap border-0 rounded-3 px-5 py-4">
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

            @foreach ($pre_employment_docs as $pre_employment_doc)
                @php
                    $doc_parts = explode('(', $pre_employment_doc->document_name, 2);
                    $doc_name = trim($doc_parts[0]);
                    $doc_hint = isset($doc_parts[1]) ? '(' . rtrim($doc_parts[1], ')') . ')' : null;
                @endphp

                <tr class="border-2 rounded-2 outline" style="height: 100px; vertical-align: middle;">
                    <form action="" wire:model="document_id" name="{{ $pre_employment_doc->document_id }}">
                        <td class="">
                            <div class="fw-bold">{{ $doc_name }}

                                @isset($doc_hint)
                                    <div class="small">
                                        {{ $doc_hint }}
                                    </div>
                                @endisset
                            </div>
                        </td>
                        <td>
                            <x-status-badge color="danger">Invalid</x-status-badge>
                        </td>
                        <td><button
                                class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                                Attachment</button></td>
                        <td><button class="btn btn-primary"> <i class="icon p-1  d-inline"
                                    data-lucide="plus-circle"></i>
                                Upload</button></td>
                    </form>
                </tr>
            @endforeach

            <tr class="" x-intersect="$wire.loadMore()">
                <td colspan="4">
                    load more
                </td>
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

    </table>
</section>

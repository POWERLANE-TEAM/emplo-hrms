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
        <td><button class="btn bg-transparent text-decoration-underline text-capitalize text-nowrap">View
                Attachment</button></td>
        <td><button class="btn btn-primary"> <i class="icon p-1  d-inline" data-lucide="plus-circle"></i>
                Upload</button></td>
    </form>
</tr>

@use(App\Enums\IssueConfidentiality)
@use(App\Enums\IssueStatus)
@use(Illuminate\View\ComponentAttributeBag)

@props([
    'modalId' => 'updateIssueStatusModal',
])

@php
    $isOpen = $issue->status === IssueStatus::OPEN->value;
@endphp

<div>
    <x-modals.dialog :id="$modalId">
        <x-slot:title>
            <h1 class="modal-title fs-5 fw-bold" id="{{ $modalId }}">
                {{ __('Issue Report Review Details') }}
            </h1>
            <button data-bs-dismiss="modal" class="btn-close" aria-label="Close"></button>        
        </x-slot:title>
        <x-slot:content>
            <div class="row mb-3">
                @if ($issue->status_marker)
                    <div class="col-5">
                        <div class="text-start">{{ __('Marked by: ') }}</div>
                        <div class="d-flex flex-column align-items-center text-secondary-emphasis">
                            <div class="text-center">
                                <a href="{{ route("{$routePrefix}.employees.information", ['employee' => $this->statusMarker->employeeId]) }}">
                                    <img 
                                        src="{{ $this->statusMarker->photo }}" 
                                        alt="Employee photo" 
                                        class="rounded-circle mb-2"
                                        height="70"
                                        width="70" 
                                        style="object-fit: cover;"
                                    />
                                </a>
                                <div class="fw-semibold fs-5">
                                    {{ $this->statusMarker->name }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ $this->statusMarker->jobTitle }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "Level {$this->statusMarker->jobLevel}: {$this->statusMarker->jobLevelName}" }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "Employee ID: {$this->statusMarker->employeeId}" }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ $this->statusMarker->employment }}
                                </div>
                                <div class="text-muted fs-6">
                                    {{ "{$this->statusMarker->shift} ({$this->statusMarker->shiftSchedule}) "}}
                                </div>        
                            </div>
                        </div>
                    </div>                    
                @endif
                
                <div class="col-{{ $isOpen ? '12' : '7' }}">
                    <label for="resolutionDesc" class="col-form-label">
                        {{ $isOpen ? __('Resolution: ') : __('Given Resolution') }}
                    </label>
                    <textarea 
                        wire:model="resolution" 
                        id="resolutionDesc" 
                        rows="10" 
                        class="form-control" 
                        @readonly(! $isOpen)
                    >
                    </textarea>                    
                </div>
                @error('resolution')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                @enderror
            </div>
        </x-slot:content>
        <x-slot:footer>
            @if ($isOpen)
                <button 
                    wire:click="closeIssue" 
                    class="fw-light btn btn-danger"
                    wire:loading.attr="disabled"
                    wire:target="closeIssue, markIssueResolve"
                >
                    {{ __('Close (won\'t work on)') }}
                </button>
                <button 
                    wire:click="markIssueResolve" 
                    class="fw-light btn btn-primary"
                    wire:loading.attr="disabled"
                    wire:target="closeIssue, markIssueResolve"
                >
                    {{ __('Mark as Resolved') }}
                </button>
            @else
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="text-{{ IssueStatus::from($issue->status)->getColor() }}">
                        <div>
                            {!! $isOpen
                                ? __('Issue Resolution')
                                : __('<i class="icon me-2 icon-slarge d-inline" 
                                        data-lucide="'.IssueStatus::from($issue->status)->getIcon().'">
                                    </i>'.'Marked as '.IssueStatus::from($issue->status)->getLabel()
                                )
                            !!}                    
                        </div>
                    </div>
                    <div>{{ __("Marked on {$issue->status_marked_at}") }}</div>
                </div>
            @endif
        </x-slot:footer>
    </x-modals.dialog>

    <x-breadcrumbs>
        <x-slot:breadcrumbs>
            <x-breadcrumb :href="'#'">
                {{ __('Issues') }}
            </x-breadcrumb>
            <x-breadcrumb :active="request()->routeIs($routePrefix . '.relations.issues.show')">
                {{ __('Reported Issue') }}
            </x-breadcrumb>
        </x-slot:breadcrumbs>
    </x-breadcrumbs>

    <div class="row">
        <div class="col-7">
            <x-headings.main-heading 
                :isHeading="true" 
                :containerAttributes="new ComponentAttributeBag(['class' => 'ps-2 pt-2 pb-1 ms-n1'])" 
                :overrideContainerClass="true"
            >
                <x-slot:heading>
                    {{ $issue->filed_at }}
                    <x-status-badge 
                        color="{{ IssueStatus::from($issue->status)->getColor() }}"
                    >
                        {{ IssueStatus::from($issue->status)->getLabel() }}
                    </x-status-badge>

                </x-slot:heading>
            </x-headings.main-heading>
        </div>

        <div class="col-5 d-flex align-items-start justify-content-end">
            <div class="" x-cloak>
                @if (! $isOpen)
                    <button onclick="openModal('{{ $modalId }}')" type="submit" name="submit"
                        class="btn btn-{{ IssueStatus::from($issue->status)->getColor() }} fw-light">
                        <i class="icon icon-slarge d-inline me-1" data-lucide="{{ IssueStatus::from($issue->status)->getIcon() }}"></i>
                        {{ __('View Review Details') }}
                    </button>
                @endif
                <span class="ms-1">
                    <button
                        type="button"
                        class="btn btn-sm py-0 no-hover-border hover-opacity"
                        data-bs-toggle="tooltip" title="Download Report"
                    >
                        <i class="icon icon-slarge text-secondary-emphasis" data-lucide="download"></i>
                    </button>                        
                </span>
            </div>
        </div>
    </div>

    <section class="mb-5 mt-0">
        <div class="px-3 mb-4">
            <div class="row">
                <div class="col-7">
                    <div class="fw-bold mb-2">{{ __('Type of Issue') }}</div>
                    <ul>
                        @foreach ($issue->types as $type)
                            <li wire:key="{{ $type->issue_type_id }}">
                                {{ $type->issue_type_name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row pt-2">
                <div class="col">
                    <x-form.display.boxed-input-display label="{{ __('DateTime Issue Occurred') }}" data="{{ $issue->occured_at }}" />
                </div>

                <div class="col">
                    <x-form.display.boxed-input-display label="{{ __('Confidentiality Preference') }}" data="{{ IssueConfidentiality::from($issue->confidentiality)->getLabel() }}"
                        :tooltip="['modalId' => 'aboutConfidentialityPref']" />
                </div>
            </div>

            <div class="row pt-2">
                <div class="col">
                    <x-form.display.boxed-input-display label="{{ __('Description') }}"
                        data="{{ $issue->issue_description }}" />
                </div>
            </div>

            <div class="row pt-2">
                <div class="col">
                    <x-form.boxed-textarea-attachment id="supporting_info" label="{{ __('Supporting Information') }}" :nonce="$nonce"
                        description=""
                        :readonly="true">
                    <x-slot:preview>
                        @if ($issue->attachments)
                            @foreach ($issue->attachments as $attachment)
                                <div class="attachment-item d-inline-flex align-items-center me-2">
                                    <a 
                                        href="{{ route("{$routePrefix}.relations.issues.attachments.show", ['attachment' => $attachment->attachment]) }}" 
                                        target="__blank" 
                                        class="text-info text-decoration-underline me-1" 
                                        title="File Name">{{ $attachment->attachment_name }}
                             
                                    </a>
                                    <a 
                                        href="{{ route("{$routePrefix}.relations.issues.download", ['attachment' => $attachment->attachment]) }}"
                                        target="__blank"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-sm py-0 px-1 no-hover-border hover-opacity"
                                            data-bs-toggle="tooltip" title="Download">
                                            <i class="icon icon-large text-info" data-lucide="download"></i>
                                        </button>
                                    </a>                                
                                </div>
                            @endforeach
                        @endif
                    </x-slot:preview>
                    </x-form.boxed-textarea-attachment>
                </div>
            </div>

            <div class="row pt-2">
                <div class="col">
                    <x-form.display.boxed-input-display label="{{ __('Desired Resolution') }}"
                        data="{{ $issue?->desired_resolution ?? __('None provided.') }}"
                        description="{{ __('Desired resolution of the issue reporter.') }}" />
                </div>
            </div>
        </div>
    </section>
</div>
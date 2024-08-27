<x-input.search-group wire:ignore container_class="col-8 col-md-4" class="justify-content-center w-100">
    <x-slot:right_icon>
        <i data-lucide="search"></i>
    </x-slot:right_icon>
    <x-input.search type="search" x-on:input.debounce.300ms="$dispatch('job-searched', [$event.target.value])"
        placeholder="Search job titles or companies"></x-input.search>
</x-input.search-group>

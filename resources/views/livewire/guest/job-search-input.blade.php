<x-form.search-group wire:ignore nonce="csp_nonce()" container_class="col-8 col-md-4"
    class="justify-content-center w-100 icon-inside-right">
    <x-slot:right_icon>
        <x-icons.white-search-1 />
    </x-slot:right_icon>
    <x-form.search type="search" x-on:input.debounce.300ms="$dispatch('job-searched', [$event.target.value])"
        placeholder="Search job titles or companies" class="job-search-input w-100" />
</x-form.search-group>
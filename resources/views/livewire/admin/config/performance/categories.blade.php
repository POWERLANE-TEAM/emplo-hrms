<section>
    <p class="">{{ __('Performance categories encompass the key areas of evaluation used to assess an employee\'s work,
        contributions and overall performance.') }}</p>

    @php
        $head = array_map(function ($item) {
            return "<div class='fw-bold text-primary fs-5'>{$item['head']}</div>";
        }, $this->categories);

        $subhead = array_map(function ($item) {
            return "<div class='text-muted'>{$item['subhead']}</div>";
        }, $this->categories);        
    @endphp

    {{-- Draggable Grid Table --}}
    <livewire:blocks.dragdrop.show-mult-drag-data 
        :items="$this->categories" 
        :editCallback="'openEditCategoriesModal'"
        :head="$head"
        :subhead="$subhead"
    />

    {{-- Add Category Button --}}
    <x-buttons.dotted-btn-open-modal label="Add Category" modal="addCategory" :disabled="false" />
</section>

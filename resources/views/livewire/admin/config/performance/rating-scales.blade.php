<section>

    <p>{{ __('The assigned numerical ratings used to rate an employee\'s performance in a category.') }}</p>

    @php
        $head = array_map(function ($item) {
            return "<span class='fw-bold text-primary'>{$item['head']} =</span>";
        }, $this->ratingScales);

        $subhead = array_map(function ($item) {
            return "<span class='text-muted'>{$item['subhead']}</span>";
        }, $this->ratingScales);        
    @endphp


    {{-- Draggable Grid Table --}}
    <livewire:blocks.dragdrop.show-mult-drag-data 
        :items="$this->ratingScales" 
        :editCallback="'openEditPerfScalesModal'"
        :head="$head"
        :subhead="$subhead" 
    />

    {{-- Add Category Button --}}
    <x-buttons.dotted-btn-open-modal label="Add Performance Scale" modal="addPerfScale" :disabled="false" />
</section>

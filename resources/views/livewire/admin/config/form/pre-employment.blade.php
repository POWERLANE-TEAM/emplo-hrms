{{--
* |--------------------------------------------------------------------------
* | Pre-Employment Requirements Section
* |--------------------------------------------------------------------------
--}}

<section>
    <p class="py-2">{{ __('Pre-employment requirements are the list of documents or attachments that pre-employed applicants needs
        to submit before proceeding.') }}</p>

    {{-- Grid Table of Pre-Emp Requirements --}}    
    <livewire:blocks.dragdrop.show-draggable-data :items="$this->requirements" />
        
    {{-- Add Another Pre-Emp Field --}}
    <livewire:blocks.inputs.add-drag-item
        :label="'Add Pre-Employment Requirement'"
        :required="true"
        :id="'pre-emp-input'"
        :name="'pre-emp-input'"
    />
    
</section>

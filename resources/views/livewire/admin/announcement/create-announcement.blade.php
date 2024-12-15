<section class="mx-2">
    <form>
        {{-- Input field for: Announcement Title --}}
        <x-form.boxed-input-text 
            id="announcement_title" 
            label="Announcement Title" 
            name="state.title"
            :nonce="$nonce" 
            :required="true"
        >
        </x-form.boxed-input-text>
        @error('state.title')
            <div class="invalid-feedback" role="alert"> {{ $message }} </div>
        @enderror

        {{-- Multiselect Dropwdown for: Job Family --}}
        <x-form.multi-select-dropdown
            x-cloak 
            id="job_fam" 
            label="Job Family" 
            name="state.visibleTo" 
            :nonce="$nonce" 
            :required="true" 
            :options="$this->jobFamilies" 
        >
        </x-form.multi-select-dropdown>
        @error('state.visibleTo')
            <div class="invalid-feedback" role="alert"> {{ $message }} </div>
        @enderror

        {{-- Textarea field for: Description --}}
        <x-form.boxed-textarea 
            id="announcement_desc"
            label="Description" 
            name="state.description" 
            :nonce="$nonce"
            :rows="6" 
            :required="true" 
        />
        @error('state.description')
            <div class="invalid-feedback" role="alert"> {{ $message }} </div>
        @enderror

        {{-- Submit Button --}}
        <x-buttons.main-btn id="post_announcement" label="Post Announcement" wire:click.prevent="save" :nonce="$nonce"
            :disabled="false" class="w-25" :loading="'Posting...'" />
    </form>
</section>


@script
<script>
    Livewire.hook('morph.added', ({ el }) => {
        console.log('Livewire morph added triggered for element:', el);
        lucide.createIcons();
    });

    Livewire.on('show-toast', (data) => {
        const toastData = Array.isArray(data) && data.length > 0 ? data[0] : data;
        showToast(toastData.type, toastData.message);
    });
</script>

@endscript
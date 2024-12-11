{{--
* |--------------------------------------------------------------------------
* | Boxed: Text Area with Attachments on the Right
* |--------------------------------------------------------------------------
--}}

@props(['label', 'nonce', 'required' => false, 'description' => null, 'readonly' => false])

<label for="{{ $attributes->get('id') }}" class="mb-1 fw-semibold">
    {{ $label }}
    {{-- Conditionally display the red asterisk for required fields --}}
    @if ($required)
        <span class="text-danger">*</span>
    @endif
</label>

{{-- Optional description below the label --}}
@if ($description)
    <p class="fs-7 mb-3">{!! $description !!}</p>
@endif

<div class="input-group mb-3 position-relative" id="custom-textarea-container">
    {{-- Editable Content Area Styled Like a Textarea --}}
    <div id="{{ $attributes->get('id') }}" contenteditable="{{ $readonly ? 'false' : 'true' }}"
        class="form-control border ps-3 rounded text-start"
        style="min-height: 150px; overflow-y: auto; padding-bottom: 40px;" nonce="{{ $nonce }}"
        {{ $readonly ? 'disabled' : '' }} aria-owns="{{ $attributes->get('id') }}-feedback"></div>

    {{-- Attachments Section --}}
    <div id="attachments-container" class="attachments-wrapper rounded-bottom">
        {{-- Attachments List --}}
        <div class="attachments-list flex-grow-1" id="attachments-list">
            {{-- Dynamically added attachment links will appear here --}}
        </div>

        {{-- Attach Files Button (Only visible if not in readonly mode) --}}
        @if (!$readonly)
            <label
                class="btn no-hover-border btn-sm hover-opacity d-inline-flex align-items-center justify-content-center attach-files-button">
                <i data-lucide="paperclip" class="icon text-primary icon-large"></i>
                <input type="file" class="d-none" multiple onchange="handleAttachments(this)">
            </label>
        @endif
    </div>
</div>

{{-- JavaScript for Managing Attachments --}}
<script>
    function handleAttachments(input) {
        const attachmentsList = document.getElementById('attachments-list');
        const files = Array.from(input.files);

        files.forEach((file, index) => {
            // Create a container for the attachment and its remove button
            const attachmentContainer = document.createElement('div');
            attachmentContainer.className = 'attachment-item d-inline-flex align-items-center me-2';

            // Create the clickable attachment link
            const attachmentLink = document.createElement('a');
            attachmentLink.href = '#'; // Replace with actual upload URL if needed
            attachmentLink.textContent = file.name;
            attachmentLink.className = 'text-info text-decoration-underline me-1';

            // Create the remove button
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-sm py-0 px-1 no-hover-border hover-opacity';
            removeButton.textContent = '✖';
            removeButton.setAttribute('data-bs-toggle', 'tooltip');
            removeButton.setAttribute('title', 'Remove attachment');
            removeButton.onclick = () => {
                attachmentsList.removeChild(attachmentContainer);
            };

            document.body.appendChild(removeButton);

            // Call the function to initialize tooltip on the dynamically created button
            initializeTooltipsOnDynamicElements();

            // Append the link and button to the container
            attachmentContainer.appendChild(attachmentLink);
            attachmentContainer.appendChild(removeButton);

            // Add the container to the attachments list
            attachmentsList.appendChild(attachmentContainer);
        });

        // Reset the input value to allow re-adding the same file
        input.value = '';
    }
</script>

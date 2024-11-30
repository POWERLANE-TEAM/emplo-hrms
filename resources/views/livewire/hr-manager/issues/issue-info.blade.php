<div>
    <div class="px-3 mb-4">

        <!-- SECTION: Type of Issue / Complaint -->
        <div class="row">
            <div class="col">
                <p class="fw-bold">Type of Issue / Complaint</p>

                <ul>
                    <li>Workplace Harassment</li>
                    <li>Safety Concerns</li>
                    <li>Performance Appraisal Disputes</li>
                </ul>

            </div>
        </div>

        <!-- SECTION: Date Issue Occured / Confidentiality Preference -->
        <div class="row pt-2">
            <div class="col">
                <x-form.display.boxed-input-display label="Date Issue Occured" data="11/21/2024" />
                <!-- BACK-END REPLACE: Date Issue Occured -->
            </div>

            <div class="col">
                <x-form.display.boxed-input-display label="Confidentiality Preference" data="Internal Use Only"
                    :tooltip="['modalId' => 'aboutConfidentialityPref']" />
                <!-- BACK-END REPLACE: Confidentiality Preference -->
            </div>
        </div>

        <!-- SECTION: Supporting Information -->
        <div class="row pt-2">
            <div class="col">
                <x-form.display.boxed-input-display label="Description"
                    data="I am writing to formally report a series of troubling incidents involving my supervisor. Over the past several months, I have experienced and observed a pattern of inappropriate behavior, including derogatory comments made during team meetings and consistent favoritism towards certain team members. This behavior has created a hostile work environment that affects my ability to perform my job effectively and has made me feel uncomfortable and marginalized within the team."
                    description="Detailed description of the issue or complaint." />
                <!-- BACK-END REPLACE: Description of the Issue -->
            </div>
        </div>

        <!-- SECTION: Supporting Information -->
        <div class="row pt-2">
            <div class="col">
                <!-- BACK-END REPLACE: Processing of supporting information and attachments. -->
                <x-form.boxed-textarea-attachment id="supporting_info" label="Supporting Information" :nonce="$nonce"
                    description="Relevant information or supporting evidence related to the complaint."
                    :readonly="true">
                </x-form.boxed-textarea-attachment>
            </div>
        </div>

        <!-- SECTION: Desired Resolution -->
        <div class="row pt-2">
            <div class="col">
                <x-form.display.boxed-input-display label="Desired Resolution"
                    data="I am writing to formally report a series of troubling incidents involving my supervisor. Over the past several months, I have experienced and observed a pattern of inappropriate behavior, including derogatory comments made during team meetings and consistent favoritism towards certain team members. This behavior has created a hostile work environment that affects my ability to perform my job effectively and has made me feel uncomfortable and marginalized within the team."
                    description="Desired resolution of the complainant." />
                <!-- BACK-END REPLACE: Description of the Issue -->
            </div>
        </div>

        <!-- Button: Mark as Resolved -->
        <!-- Statement of the issue's resolution before completely marking as resolved. -->
        <div class="pt-3">
            <button onclick="openModal('addIssueResolution')" type="submit" name="submit"
                class="btn btn-primary btn-lg col-6 w-25">Mark as Resolved</button>
        </div>
    </div>
</div>

<!-- BACK-END REPLACE: Simulation of back-end data. Remove if no longer needed. -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if in read-only mode
        const isReadOnly = true; // Set this dynamically if needed, like based on Blade variable
        const attachmentsList = document.getElementById('attachments-list');
        const textArea = document.getElementById('supporting_info');  // REPLACE: ID of the textarea.

        if (isReadOnly) {
            // 1. Add placeholder data to the contenteditable (textarea)
            if (textArea && !textArea.textContent.trim()) { // Ensure there's no existing content
                textArea.textContent = 'This is placeholder content. Please add any relevant information or supporting evidence related to your complaint.';
            }

            // 2. Add placeholder attachments
            if (attachmentsList) {
                const placeholders = [
                    { name: "Sample Document 1.pdf" },
                    { name: "Supporting Evidence.pdf" },
                    { name: "Sample Image 2.jpg" }
                ];

                placeholders.forEach(placeholder => {
                    const attachmentContainer = document.createElement('div');
                    attachmentContainer.className = 'attachment-item d-inline-flex align-items-center me-2';

                    const attachmentLink = document.createElement('a');
                    attachmentLink.href = '#';
                    attachmentLink.textContent = placeholder.name;
                    attachmentLink.className = 'text-info text-decoration-underline me-1';

                    // No remove button for read-only mode
                    attachmentContainer.appendChild(attachmentLink);
                    attachmentsList.appendChild(attachmentContainer);
                });
            }
        }
    });
</script>
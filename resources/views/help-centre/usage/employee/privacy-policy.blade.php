@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Privacy Policy')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <section>

        <p>Powerlane Resources, Inc. (“we,” “us,” or “our”) respects the privacy of all individuals and recognizes its
            responsibility to protect personal data in accordance with Republic Act No. 10173, the Data Privacy Act of
            2012 (DPA). This Privacy Policy provides a comprehensive overview of how we process, protect, and manage
            personal data collected and stored within our Human Resource Management System (EMPLO).</p>

        <h4><strong>Data We Collect</strong></h4>
        <p>We collect and process the following types of personal data:</p>
        <ol>
            <li>
                <strong>Personal Information</strong>
                <ul>
                    <li>Full name, contact details, address, email, and phone number.</li>
                    <li>Government-issued identification (e.g., SSS, TIN, PhilHealth).</li>
                </ul>
            </li>
            <li>
                <strong>Sensitive Personal Information</strong>
                <ul>
                    <li>Biometric data (e.g., fingerprints).</li>
                    <li>Health information (e.g., medical certificates).</li>
                </ul>
            </li>
            <li>
                <strong>HR System Data</strong>
                <ul>
                    <li>Attendance logs, performance metrics, and employment records.</li>
                    <li>Login credentials, access logs, and system activity data.</li>
                </ul>
            </li>
            <li>
                <strong>Machine Learning Data</strong>
                <ul>
                    <li>Anonymized datasets used for training the EMPLO’s Resume Evaluator feature (e.g., scores, F1
                        evaluations).</li>
                </ul>
            </li>
            <li>
                <strong>Employee 201 Records</strong>
            </li>
        </ol>
        <p>The methods and procedures for discarding these data are outlined in the Data Retention Policy.</p>

        <h4><strong>Legal Basis for Processing</strong></h4>
        <p>We process data based on the following lawful criteria under the DPA:</p>
        <ul>
            <li><strong>Consent:</strong> Explicit consent is obtained during system registration and onboarding.</li>
            <li><strong>Contractual Obligations:</strong> Data is necessary to fulfill employment contracts and
                benefits.</li>
            <li><strong>Legitimate Interests:</strong> Enhancing HR operations and ensuring compliance with
                organizational policies.</li>
        </ul>

        <h4><strong>How We Use the Data</strong></h4>
        <p>We process personal data to:</p>
        <ul>
            <li>Simplify recruitment, talent evaluation, and onboarding.</li>
            <li>Track attendance and manage leave applications efficiently.</li>
            <li>Provide accurate performance assessments and reports.</li>
            <li>Securely store and manage employee records.</li>
            <li>Generate analytics for HR decision-making.</li>
        </ul>

        <h4><strong>Data Sharing and Disclosure</strong></h4>
        <p>We do not sell or share personal data with third parties without explicit consent, except under the following
            circumstances:</p>
        <ul>
            <li><strong>Legal Compliance:</strong> Data may be shared with government agencies (e.g., BIR, SSS) for
                compliance purposes.</li>
            <li><strong>Third-Party Processors:</strong> Vendors (e.g., Google Document AI) who assist in HR operations
                are contractually bound to comply with our privacy standards.</li>
        </ul>

        <h4><strong>Data Retention</strong></h4>
        <p>We retain personal data only for as long as necessary to fulfill its purpose. The details regarding how data
            are retained are outlined in the Data Retention Policy.</p>

        <h4><strong>User Rights Under the DPA</strong></h4>
        <p>As a data subject, you have the following rights:</p>
        <ul>
            <li><strong>Right to Be Informed:</strong> Know why and how your data is being processed.</li>
            <li><strong>Right to Access:</strong> Request copies of your personal data.</li>
            <li><strong>Right to Rectification:</strong> Correct inaccuracies in your data.</li>
            <li><strong>Right to Erasure:</strong> Request deletion of data no longer necessary.</li>
            <li><strong>Right to Data Portability:</strong> Obtain a copy of your data in a portable format.</li>
            <li><strong>Right to Object:</strong> Decline specific data processing activities.</li>
        </ul>

        <h4><strong>Changes to This Policy</strong></h4>
        <p>This Privacy Policy may be updated periodically. Users will be informed of significant changes through system
            notifications or official communication channels.</p>

        <h4><strong>Contact Information</strong></h4>
        <p>For inquiries, concerns, or exercise of rights under this policy, contact:</p>
        <ul>
            <li><strong>Email:</strong> pri@powerlane.net</li>
            <li><strong>Phone:</strong> 09173090481 / 09987922305</li>
            <li><strong>Address:</strong> Rowsuz Business Centre, Diversion Rd, Santa Rosa, Laguna</li>
        </ul>
    </section>


</div>
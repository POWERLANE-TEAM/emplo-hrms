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
            
        <h4><strong>Data Collection</strong></h4>
        <p>We collect personal data, including your name, contact information, educational background, employment
            history, and other relevant details provided in your application.</p>

        <h4><strong>Purpose of Data Processing</strong></h4>
        <p>Your data is processed to assess your suitability for employment, facilitate communication during the
            recruitment process, and comply with legal obligations.</p>

        <h4><strong>AI Processing</strong></h4>
        <p>EMPLO utilizes AI technologies exclusively as a resume evaluator to assist in the recruitment process.</p>

        <h4><strong>Purpose of AI Processing</strong></h4>
        <p>The AI processes the information provided in your resume, such as skills, qualifications, work experience,
            and other relevant details, to streamline application reviews and identify candidates whose qualifications
            align with our job requirements.</p>

        <h4><strong>Scope of Data Usage</strong></h4>
        <p>The data analyzed by AI is strictly limited to the information contained in your resume and is used solely
            for recruitment-related purposes.</p>

        <h4><strong>Human Oversight</strong></h4>
        <p>All decisions involving your application, including those informed by AI analysis, are reviewed by our
            recruitment team to ensure fairness, accuracy, and compliance with applicable laws.</p>

        <h4><strong>Data Protection</strong></h4>
        <p>The data processed by AI is handled with the utmost care and in accordance with our Privacy Policy, ensuring
            your information is safeguarded at all stages.</p>

        <h4><strong>Data Sharing</strong></h4>
        <ul>
            <li>Your personal data may be shared with third-party service providers who assist in our recruitment
                process.</li>
            <li>These parties are bound by confidentiality agreements and data protection laws.</li>
            <li>EMPLO specifically utilizes third-party services to facilitate background checks, resume evaluation, and
                other recruitment-related functions.</li>
        </ul>

        <h4><strong>Data Security</strong></h4>
        <p>We implement robust security measures to protect your data from unauthorized access, alteration, or
            disclosure.</p>

        <h4><strong>Your Rights</strong></h4>
        <p>Under the Data Privacy Act of 2012, you have the right to access, correct, or request deletion of your
            personal data. You may also object to the processing of your data under certain circumstances.</p>

        <h4><strong>Contact Information</strong></h4>
        <p>For inquiries or concerns regarding your data, please contact our Data Protection Officer at
            pri.recruitment@powerlane.net.</p>

        <h4><strong>Compliance with Philippine Laws and International Standards</strong></h4>
        <ul>
            <li>The Data Privacy Act of 2012 (Republic Act No. 10173): We adhere to the principles and requirements set
                forth in the DPA, ensuring lawful processing, transparency, and protection of your personal data.</li>
            <li>National Privacy Commission (NPC) Guidelines: Our AI systems comply with NPC advisories on the ethical
                use of AI in processing personal data, emphasizing transparency, accountability, and fairness.</li>
            <li>International Standards: We align our data processing activities with international standards, such as
                ISO/IEC 23894:2023, which provides guidelines on risk management for AI, and ISO/IEC 42001:2023,
                focusing on AI management systems.</li>
        </ul>

        <h4><strong>Data Retention</strong></h4>
        <p>We retain personal data only for as long as necessary to fulfill its purpose. In the case of application
            rejections:</p>

        <ul>
            <li><strong>Rejected Applications</strong>: If your application is rejected, we will immediately delete your
                data and account as it is no longer required
                for any purpose.</li>

            <li><strong>Successful Applications</strong>: For successful candidates, personal data and submitted
                documents will be retained as part of their employee
                record, managed in accordance with applicable laws and company policies.</li>
        </ul>
        <p>We do not retain rejected application data unless required by law, regulatory obligations, or for legitimate
            security purposes.</p>
    </section>


</div>
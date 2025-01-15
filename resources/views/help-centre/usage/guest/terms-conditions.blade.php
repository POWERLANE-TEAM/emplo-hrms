@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Terms and Conditions')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <section>
        <p>These Terms and Conditions (“Terms”) govern your use of the EMPLO, which is provided by Powerlane Resources,
            Inc. (“we,” “us,” or “our”). By accessing or using the EMPLO, you (“Applicant,” “you,” or “your”) agree to
            comply with and be bound by these Terms. If you do not agree to these Terms, we kindly ask that you refrain
            from using EMPLO.</p>
        <p>EMPLO is a platform used for managing the recruitment process and storing personal data of applicants to
            facilitate job applications, evaluation, and communication. Please read these Terms carefully before using
            EMPLO.</p>

        <h4><strong>1. Acceptance of Terms</strong></h4>
        <p>By submitting your application, you agree to these Terms, as well as any future updates or modifications. If
            you do not accept these Terms, you should not use the EMPLO.</p>

        <h4><strong>2. Eligibility</strong></h4>
        <p>To use the EMPLO, you must be at least 18 years old and legally capable of entering into a binding contract.
            By using the EMPLO, you represent and warrant that you meet these eligibility requirements.</p>

        <h4><strong>3. User Registration and Account Creation</strong></h4>
        <p>To access and use the EMPLO, you may need to create an account by providing certain personal information. You
            agree to:</p>
        <ul>
            <li>Provide accurate, current, and complete information during the registration process.</li>
            <li>Maintain the confidentiality of your login credentials and promptly notify us if you suspect any
                unauthorized use of your account.</li>
            <li>Use the EMPLO solely for the purpose of applying for employment and related activities.</li>
        </ul>
        <p>We reserve the right to suspend or terminate your account if we suspect any violation of these Terms or any
            fraudulent activity.</p>

        <h4><strong>4. Data Collection and Privacy</strong></h4>
        <p>The EMPLO collects and processes personal data as part of the recruitment process. By using the EMPLO, you
            consent to the collection, use, and storage of your personal data in accordance with the Privacy Policy,
            which is incorporated into these Terms by reference. Personal data may include, but is not limited to:</p>
        <ul>
            <li>Full name, contact details, government-issued identification.</li>
            <li>Application-related documents (e.g., resumes).</li>
            <li>Pre-employment requirements.</li>
            <li>Application status, interview schedules, and performance evaluation data.</li>
        </ul>
        <p>You agree to provide accurate and truthful information. Any false or misleading information may result in the
            rejection of your application or the termination of your account.</p>

        <h4><strong>5. AI Involvement</strong></h4>
        <p>The EMPLO utilizes AI technologies exclusively as a resume evaluator to assist in the recruitment process.
            The AI processes the information provided in your resume, such as skills, qualifications, work experience,
            and other relevant details, to streamline application reviews and identify candidates whose qualifications
            align with our job requirements. We assure you that the data analyzed by AI is limited to the information
            contained in your resume and is used solely for recruitment purposes. All decisions involving your
            application are reviewed by our recruitment team to ensure fairness and accuracy in the evaluation process.
        </p>

        <h4><strong>6. Use of EMPLO</strong></h4>
        <p>You agree to use the EMPLO solely for its intended purpose — applying for job opportunities with Powerlane
            Resources, Inc. and participating in recruitment-related activities. Specifically, you agree not to:</p>
        <ul>
            <li>Use the EMPLO for any illegal, fraudulent, or harmful activities.</li>
            <li>Upload, post, or transmit any content that is unlawful, harmful, defamatory, obscene, or infringes on
                the rights of others.</li>
            <li>Access or attempt to access accounts, data, or systems without proper authorization.</li>
            <li>Use automated means (e.g., bots, scrapers) to access or collect information from the EMPLO.</li>
        </ul>
        <p>You acknowledge that the EMPLO may be subject to regular updates and maintenance, which may temporarily
            affect your access to certain features.</p>

        <h4><strong>7. Communication and Notifications</strong></h4>
        <p>By using the EMPLO, you consent to receive communications from Powerlane Resources, Inc. regarding your
            application, interview schedules, job offers, and updates to these Terms or the Privacy Policy.
            Communications may be sent via email, system notifications, or other means as deemed appropriate.</p>
        <p>You are responsible for ensuring that your contact information is up to date in the EMPLO to receive timely
            notifications.</p>

        <h4><strong>8. Third-Party Services</strong></h4>
        <p>The EMPLO may integrate with third-party services or vendors to facilitate certain features, such as resume
            evaluation or background checks. You consent to the use of these third-party services in accordance with
            their respective terms and privacy policies. We are not responsible for the content or actions of
            third-party services.</p>

        <h4><strong>9. Data Retention and Deletion</strong></h4>
        <p>For details on data retention, please refer to our <a href="/information-centre?section=guest-privacy-policy" class="text-link-blue hover-opacity">Privacy Policy</a>.</p>

        <h4><strong>10. Termination of Account</strong></h4>
        <p>We reserve the right to suspend or terminate your account if:</p>
        <ul>
            <li>You violate these Terms.</li>
            <li>You engage in fraudulent or unlawful activities.</li>
            <li>Your application is deemed incomplete, misleading, or inaccurate.</li>
        </ul>
        <p>You may terminate your account at any time by contacting us.</p>

        <h4><strong>11. Changes to the Terms and Conditions</strong></h4>
        <p>We may update these Terms from time to time to reflect changes in our practices or legal requirements. We
            will notify you of any material changes by posting the updated Terms on the EMPLO or via email. Your
            continued use of the EMPLO after such changes constitutes your acceptance of the revised Terms.</p>

        <h4><strong>12. Governing Law and Dispute Resolution</strong></h4>
        <p>These Terms will be governed by and construed in accordance with the laws of the Philippines. Any disputes
            arising under or in connection with these Terms shall be subject to the exclusive jurisdiction of the courts
            of the Philippines.</p>

        <h4><strong>13. Contact Information</strong></h4>
        <p>If you have any questions or concerns regarding these Terms or your use of the EMPLO, please contact us at:
        </p>
        <ul>
            <li>Email: pri.recruitment@powerlane.net</li>
            <li>Phone: 09173090481 / 09987922305</li>
            <li>Address: Rowsuz Business Centre, Diversion Rd</li>
        </ul>
        <p>By using the EMPLO, you acknowledge that you have read, understood, and agree to abide by these Terms and
            Conditions.</p>
    </section>
</d>
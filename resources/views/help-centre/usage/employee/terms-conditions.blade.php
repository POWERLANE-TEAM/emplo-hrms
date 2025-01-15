@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Terms & Conditions')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <section>
        <p>These Terms and Conditions (“Terms”) govern your use of the EMPLO, which is provided by Powerlane Resources,
            Inc. (“we,” “us,” or “our”). By accessing or using the EMPLO, you (“Employee,” “you,” or “your”) agree to
            comply with and be bound by these Terms. If you do not agree to these Terms, do not use the EMPLO.</p>
        <p>The EMPLO is a platform designed to streamline various HR functions, including but not limited to attendance,
            performance evaluations, employee records, and other HR-related tasks. Please read these Terms carefully
            before using the EMPLO.</p>

        <h4><strong>1. Acceptance of Terms</strong></h4>
        <p>By accessing and using the EMPLO, you agree to these Terms, as well as any future updates or modifications.
            If you do not agree to these Terms, you should not use the EMPLO.</p>

        <h4><strong>2. Eligibility</strong></h4>
        <p>To use the EMPLO, you must be an employee of Powerlane Resources, Inc. with authorized access to the system.
            By using the EMPLO, you represent and warrant that you are a current employee and have the appropriate
            permissions to use the system for HR-related activities.</p>

        <h4><strong>3. User Registration and Account Creation</strong></h4>
        <p>To access and use the EMPLO, you may need to create an account or log in using your employee credentials. You
            agree to:</p>
        <ul>
            <li>Provide accurate, current, and complete information during the registration process or when updating
                your employee profile.</li>
            <li>Maintain the confidentiality of your login credentials and promptly notify us if you suspect any
                unauthorized use of your account.</li>
            <li>Use the EMPLO solely for the purpose of managing HR-related activities, including but not limited to
                attendance tracking, performance evaluations, and accessing HR records.</li>
            <li>We reserve the right to suspend or terminate your account if we suspect any violation of these Terms or
                if you misuse the system in any way.</li>
        </ul>

        <h4><strong>4. Data Collection and Privacy</strong></h4>
        <p>The EMPLO collects and processes personal and sensitive data as part of its HR functions. By using the EMPLO,
            you consent to the collection, use, and storage of your personal data in accordance with the Privacy Policy,
            which is incorporated into these Terms by reference. Personal data may include, but is not limited to:</p>
        <ul>
            <li>Personal Information: Full name, contact details, employee ID, government-issued identification (e.g.,
                SSS, TIN).</li>
            <li>Sensitive Personal Information: Biometric data (e.g., fingerprints), health-related information,
                performance evaluation data.</li>
            <li>HR System Data: Attendance logs, leave records, job-related documents, training history, and other
                employment-related data.</li>
            <li>Machine Learning Data: Anonymized data for system optimization and evaluation, such as performance and
                attendance metrics.</li>
        </ul>
        <p>You agree to provide accurate and truthful information, and any false or misleading information may result in
            disciplinary action, up to and including termination.</p>

        <h4><strong>5. Use of the EMPLO</strong></h4>
        <p>You agree to use the EMPLO solely for its intended purpose — managing HR-related activities such as
            attendance, leave requests, performance evaluations, and accessing your employment records. Specifically,
            you agree not to:</p>
        <ul>
            <li>Use the EMPLO for any illegal, fraudulent, or harmful activities.</li>
            <li>Upload, post, or transmit any content that is unlawful, harmful, defamatory, or infringes on the rights
                of others.</li>
            <li>Access or attempt to access accounts, data, or systems without proper authorization.</li>
            <li>Use automated means (e.g., bots, scrapers) to access or collect information from the EMPLO.</li>
        </ul>
        <p>You acknowledge that the EMPLO may be subject to regular updates and maintenance, which may temporarily
            affect your access to certain features.</p>

        <h4><strong>6. Employee Rights and Access</strong></h4>
        <p>As an employee, you have the right to access certain data within the EMPLO, including your personal records,
            attendance, leave history, and performance evaluations. You also have the right to:</p>
        <ul>
            <li>Request corrections to your data if it is inaccurate.</li>
            <li>Request deletion of data that is no longer required for the purpose for which it was collected, subject
                to legal requirements and company policies.</li>
            <li>Request access to specific data within the EMPLO related to your employment.</li>
        </ul>

        <h4><strong>7. AI Involvement</strong></h4>
        <p>EMPLO utilizes AI technologies exclusively to assist in the performance improvement plan process by analyzing
            the results of performance evaluations to provide insights and recommendations for potential areas of focus.
            These AI-generated insights are carefully reviewed and validated by our management team to ensure they align
            with organizational objectives and uphold fairness and accuracy in the process.</p>

        <h4><strong>8. Communication and Notifications</strong></h4>
        <p>By using the EMPLO, you consent to receive communications from Powerlane Resources, Inc. regarding your
            attendance, leave requests, performance evaluations, company updates, and changes to these Terms or the
            Privacy Policy. Communications may be sent via email, system notifications, or other means as deemed
            appropriate.</p>
        <p>You are responsible for ensuring that your contact information is up to date in the EMPLO to receive timely
            notifications.</p>

        <h4><strong>9. Third-Party Services</strong></h4>
        <p>The EMPLO may integrate with third-party services or vendors to facilitate certain features, such as
            biometric attendance tracking, resume evaluation, and performance management. You consent to the use of
            these third-party services in accordance with their respective terms and privacy policies. We are not
            responsible for the content or actions of third-party services.</p>

        <h4><strong>10. Data Retention and Deletion</strong></h4>
        <p>For details on data retention, please refer to the <a href="/information-centre?section=employee-privacy-policy" class="text-link-blue hover-opacity">Privacy Policy</a> and <a href="/information-centre?section=data-retention" class="text-link-blue hover-opacity">Data Retention Policy</a>.</p>

        <h4><strong>11. Termination of Account</strong></h4>
        <p>We reserve the right to suspend or terminate your account if:</p>
        <ul>
            <li>You violate these Terms or engage in any unauthorized or illegal activities.</li>
            <li>Your account is inactive for a prolonged period, or you no longer meet the criteria for access.</li>
        </ul>
        <p>You may request termination of your account in accordance with company policies. However, access to certain
            HR data may remain available to authorized personnel as required by legal or operational obligations.</p>

        <h4><strong>12. Limitation of Liability</strong></h4>
        <p>To the fullest extent permitted by law, Powerlane Resources, Inc. and its affiliates, officers, employees,
            and agents will not be liable for any indirect, incidental, consequential, special, or punitive damages
            arising out of or in connection with your use of the EMPLO. This includes, but is not limited to, loss of
            data, HR system errors, or disruption of services.</p>
        <p>Our total liability to you for any claims arising from your use of the EMPLO is limited to the amount you
            have paid, if any, for using the service during the 12-month period preceding the event that gave rise to
            the claim.</p>

        <h4><strong>13. Indemnification</strong></h4>
        <p>You agree to indemnify and hold Powerlane Resources, Inc., its officers, employees, and agents harmless from
            any claims, damages, liabilities, and expenses arising from your breach of these Terms, your use of the
            EMPLO, or your violation of any applicable laws.</p>

        <h4><strong>14. Changes to the Terms and Conditions</strong></h4>
        <p>We may update these Terms from time to time to reflect changes in our practices or legal requirements. We
            will notify you of any material changes by posting the updated Terms on the EMPLO or via email. Your
            continued use of the EMPLO after such changes constitutes your acceptance of the revised Terms.</p>

        <h4><strong>15. Governing Law and Dispute Resolution</strong></h4>
        <p>These Terms will be governed by and construed in accordance with the laws of the Philippines. Any disputes
            arising under or in connection with these Terms shall be subject to the exclusive jurisdiction of the courts
            of the Philippines.</p>

        <h4><strong>16. Contact Information</strong></h4>
        <p>If you have any questions or concerns regarding these Terms or your use of the EMPLO, please contact us at:
        </p>
        <ul>
            <li>Email: pri@powerlane.net</li>
            <li>Phone: 09173090481 / 09987922305</li>
            <li>Address: Rowsuz Business Centre, Diversion Rd, Santa Rosa, Laguna</li>
        </ul>

        <p>By using the EMPLO, you acknowledge that you have read, understood, and agree to abide by these Terms and
            Conditions.</p>
    </section>


</div>
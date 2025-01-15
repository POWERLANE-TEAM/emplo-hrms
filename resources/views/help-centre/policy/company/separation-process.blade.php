@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Separation Policy')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <body>

        <p>This policy outlines the procedures for retaining and disposing of employee accounts and associated data
            following resignation, ensuring compliance with company policies, Philippine data retention laws, and
            practical operational requirements. We retain personal data only for as long as necessary to fulfill its
            purpose.</p>

        <h4>1. <strong>Account Retention Period</strong></h4>
        <ul>
            <li><strong>Duration:</strong> Employee accounts will be retained for <strong>4 years</strong> from the date
                of resignation, aligning with the company's document retention policy.</li>
            <li><strong>Purpose:</strong> This retention period allows the system to maintain access to employment
                records, tax documentation, or references that might be needed during this time frame.</li>
        </ul>

        <h4>2. <strong>Implementation Options</strong></h4>
        <ol>
            <li><strong>Active but Limited Access (Grace Period):</strong>
                <ul>
                    <li><strong>Access Period:</strong> Following resignation, the employee's account will remain active
                        for a short period of <strong>3 months</strong>.</li>
                    <li><strong>Purpose:</strong> This grace period enables the former employee to download personal
                        documents such as payslips, certificates, or other relevant records.</li>
                </ul>
            </li>
            <li><strong>Transition to Inactive Status:</strong> After the grace period, the account will be converted to
                inactive status, restricting further access.</li>
            <li><strong>Inactive Status:</strong>
                <ul>
                    <li><strong>Definition:</strong> Once the grace period concludes, the account becomes inactive but
                        remains within the system.</li>
                    <li><strong>Access by HR/Admin:</strong> Human Resources or administrative personnel can temporarily
                        reactivate the account if needed for verification, clearance, or reference purposes.</li>
                </ul>
            </li>
            <li><strong>Data Deletion:</strong>
                <ul>
                    <li><strong>Deletion Timeline:</strong> After the <strong>4-year</strong> retention period, the
                        account and all associated data will be permanently deleted.</li>
                    <li><strong>Exceptions:</strong> If legal requirements or company policies mandate a longer
                        retention period, the data will be retained accordingly.</li>
                </ul>
            </li>
        </ol>

        <h4>3. <strong>Compliance with Philippine Data Retention Laws</strong></h4>
        <ul>
            <li><strong>In accordance with Philippine regulations, particularly the Data Privacy Act of 2012, and
                    advisories from the National Privacy Commission, the following practices are observed:</strong></li>
            <ul>
                <li><strong>Employment Records:</strong> Employers are required to retain employment records for a
                    minimum of <strong>three years</strong> from the date of the last entry.</li>
                <li><strong>Data Subject Rights:</strong> Former employees have the right to access their personal data
                    during the retention period, subject to company policies and legal limitations.</li>
            </ul>
        </ul>

        <h4>4. <strong>Data Security and Access Control</strong></h4>
        <ul>
            <li><strong>Security Measures:</strong> All retained accounts and data are safeguarded with appropriate
                security measures to prevent unauthorized access, alteration, or disclosure.</li>
            <li><strong>Access Restrictions:</strong> Access to inactive accounts is restricted to authorized HR and
                administrative personnel for legitimate business purposes only.</li>
        </ul>

        <h4>5. <strong>Policy Review and Updates</strong></h4>
        <p>This Account Retention Policy will be reviewed annually or upon significant changes in relevant laws or
            company procedures. Updates will be communicated to all stakeholders and incorporated into the company's
            data management documentation.</p>
        <p>By adhering to this policy, Powerlane Resources, Inc. ensures responsible management of former employee
            accounts, balancing operational needs with legal compliance and the privacy rights of individuals.</p>
    </body>

</div>
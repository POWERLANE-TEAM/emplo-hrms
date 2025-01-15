@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Performance Evaluations')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <body>
        <p><strong>Effective Date:</strong> January 1, 2025</p>
        <p><strong>Last Updated:</strong> December 12, 2024</p>

        <h4>Purpose</h4>
        <p>This policy outlines the performance evaluation process for employees of Powerlane Resources Inc., including
            the frequency of evaluations for regular and probationary employees, and the consequences of failing to meet
            performance standards during the probationary period.</p>

        <h4>Regular Employees Evaluation</h4>
        <p>Regular employees will undergo annual performance evaluations. These evaluations assess job performance,
            productivity, behavior, and overall contribution to the company. The evaluation will be based on previously
            set criteria and goals.</p>

        <h4>Probationary Employees Evaluation</h4>
        <p>Probationary employees will undergo three performance evaluations during their probationary period, which
            lasts for six months. These evaluations are as follows:</p>
        <ul>
            <li><strong>3-Month Evaluation:</strong> An initial assessment to determine the employee’s suitability for
                the role and identify areas for improvement.</li>
            <li><strong>5-Month Evaluation:</strong> A mid-probation review to assess progress and performance, and to
                address any concerns.</li>
            <li><strong>Final Evaluation:</strong> A comprehensive evaluation at the end of the probationary period to
                determine whether the employee will be retained as a regular employee.</li>
        </ul>

        <h4>Failure to Pass Probationary Evaluation</h4>
        <p>If a probationary employee fails to meet performance standards during any of the evaluations, particularly
            the final evaluation, it will result in the termination of their employment. The employee will be informed
            of the reasons for their non-passing status and the decision to terminate, in compliance with Philippine
            labor laws.</p>

        <h4>Evaluation Criteria</h4>
        <p>The performance evaluation will be based on a variety of factors, including:</p>
        <ul>
            <li>Job knowledge and skills</li>
            <li>Quality and timeliness of work</li>
            <li>Teamwork and collaboration</li>
            <li>Adherence to company policies and values</li>
            <li>Attendance and punctuality</li>
        </ul>

        <h4>Employee Development</h4>
        <p>Employees who do not meet performance expectations during evaluations will receive feedback and, if
            applicable, a performance improvement plan (PIP) that outlines the areas needing improvement. For
            probationary employees, failure to show significant improvement may lead to the termination of employment at
            the end of the probationary period.</p>

        <h4>Legal Compliance</h4>
        <ul>
            <li><strong>Labor Code of the Philippines:</strong> This policy complies with the Labor Code, ensuring
                proper notification and transparency in the performance evaluation process, particularly regarding the
                rights of probationary employees.</li>
            <li><strong>Republic Act No. 10173 (Data Privacy Act):</strong> Employee evaluation data will be stored and
                processed in compliance with the Data Privacy Act, ensuring confidentiality and security.</li>
        </ul>
    </body>

</div>
@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Resume Evaluator')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <style>
        h4 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .formula {
            margin: 15px 0;
            font-style: italic;
        }

        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
    </style>

    <body>
        <p>
            The Resume Evaluator utilized a multi-layered scoring formula that categorized and weighed job
            qualifications into three levels of priority: High, Medium, and Low. The module evaluated the number of
            qualifications provided for a role, compared these with the qualifications possessed by candidates, and
            computed a total score based on weighted contributions. This score, expressed as a percentage, served as an
            indicator of a candidate's suitability for the position.
        </p>

        <h4 class="fw-bold">Job Title and Qualifications Setup</h4>
        <p>Upon the creation of a job title, you are required to specify the qualifications in terms of experience, skills, and education. These qualifications are then matched against the applicant's own, which are processed and categorized by the Resume Extractor AI. This ensures that the evaluation process is tailored to the specific requirements of each role.        </p>

        <h4 class="fw-bold">Formula:</h4>
        <p>The scoring formula is based on the following principles:</p>

        <h4 class="fw-bold">Property Weights</h4>
        <ul>
            <li>High Priority (HP): 50% of 100 points</li>
            <li>Medium Priority (MP): 30% of 100 points</li>
            <li>Low Priority (LP): 20% of 100 points</li>
        </ul>

        <h4 class="fw-bold">Variables</h4>
        <h5>1. Number of Qualifications</h4>
        <ul>
            <li>Nh = No. of HP qualifications</li>
            <li>PNm = No. of MP qualifications</li>
            <li>Nl = No. of LP qualifications</li>
        </ul>

        <h5>2. Number of Passed Qualifications</h4>
        <ul>
            <li>Qh = No. of passed HP qualification</li>
            <li>Qm = No. of passed MP qualification</li>
            <li>Ql = No. of passed LP qualification</li>
        </ul>

        <h5>3. Totals</h4>
        <ul>
            <li>P-earned total = total points earned</li>
            <li>T = total percentage</li>
        </ul>

        <h4 class="fw-bold">Total Points Earned Formula:</h4>
        <div class="formula">
            P-earned total = (50/Nh × Qh) + (30/Nm × Qm) + (20/Nl × Ql)
        </div>

        <h4 class="fw-bold">Total Percentage Formula:</h4>
        <div class="formula">
            T = (P-earned total/100) × 100
        </div>

        <h5>4. Results:</h4>
        <p>Total earned points are calculated, followed by the percentage score, which ranks candidates objectively.</p>
    </body>
</div>
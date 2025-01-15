@use ('Illuminate\View\ComponentAttributeBag')

<div>
    <header>
        <x-headings.main-heading :isHeading="true" :containerAttributes="new ComponentAttributeBag(['class' => 'pb-0 text-center'])" :overrideContainerClass="true" class="text-primary fs-1 fw-bold">
            <x-slot:heading>
                {{__('Annual Reports')}}
            </x-slot:heading>
        </x-headings.main-heading>
    </header>

    <p>Annual reports are automatically generated at the end of each year and become accessible once the year has concluded. This documentation explains each report's purpose and significance.</p>

    <h4 class="fw-bold">Available Reports</h4>

    <h4 class="fw-bold">Key Metrics Dashboard</h4>
    <p>A high-level overview of critical HR performance indicators, including:</p>
    <ul>
        <li>Incident resolution rates</li>
        <li>Issue completion status</li>
        <li>Training program completion metrics</li>
    </ul>
    <p>This dashboard helps managers quickly assess the overall state of HR operations and identify areas requiring attention.</p>

    <h4 class="fw-bold">Issue Resolution Analysis</h4>
    <p>Tracks how efficiently the organization handles and resolves workplace issues:</p>
    <ul>
        <li>Average resolution time trends</li>
        <li>Monthly and yearly resolution patterns</li>
        <li>Issue backlog analysis</li>
    </ul>
    <p>Understanding issue resolution patterns helps optimize response times and improve employee satisfaction.</p>

    <h4 class="fw-bold">Leave Utilization Insights</h4>
    <p>Monitors employee leave patterns and usage:</p>
    <ul>
        <li>Overall leave utilization rates</li>
        <li>Breakdown by leave types (sick, vacation, paternity)</li>
        <li>Available vs. used leave balance</li>
    </ul>
    <p>This data helps ensure fair leave distribution and identify potential staffing gaps.</p>

    <h4 class="fw-bold">Retention and Turnover Analysis</h4>
    <p>Provides crucial insights into workforce stability:</p>
    <ul>
        <li>Employee retention rates</li>
        <li>Turnover percentages</li>
        <li>Year-over-year comparison</li>
        <li>Total workforce changes</li>
    </ul>
    <p>These metrics help organizations understand and improve employee retention strategies.</p>

    <h4 class="fw-bold">Absenteeism Report</h4>
    <p>Tracks and analyzes employee absence patterns:</p>
    <ul>
        <li>Monthly absence trends</li>
        <li>Yearly absence totals</li>
        <li>Average monthly absences</li>
    </ul>
    <p>This information helps identify potential issues and implement preventive measures.</p>

    <h4 class="fw-bold">Average Attendance Tracking</h4>
    <p>Monitors workforce attendance patterns:</p>
    <ul>
        <li>Daily attendance rates</li>
        <li>Monthly attendance averages</li>
        <li>Attendance trends across different periods</li>
    </ul>
    <p>These insights help maintain optimal staffing levels and identify attendance issues early.</p>

    <h4 class="fw-bold">Employee Performance Metrics</h4>
    <p>Comprehensive overview of workforce performance indicators:</p>
    <ul>
        <li>Average employee tenure</li>
        <li>New hire statistics</li>
        <li>Evaluation success rates</li>
        <li>Hiring efficiency (applications vs. successful hires)</li>
    </ul>
    <p>This report helps track the effectiveness of recruitment, retention, and performance management strategies.</p>

</div>
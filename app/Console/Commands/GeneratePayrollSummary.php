<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\PayrollSummary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\PayrollSummaryService;

class GeneratePayrollSummary extends Command
{
    public function __construct(private PayrollSummaryService $payrollSummary) 
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prollsummary:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if today is end of cut-off period to generate payroll summary for each active employee.';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $latestPayroll = $this->payrollSummary->latestPayroll;

        if (now()->lte($latestPayroll->cut_off_end)) return;

        $isPayrollGenerated = PayrollSummary::firstWhere('payroll_id', $latestPayroll->payroll_id);

        if ($isPayrollGenerated) return;

        Employee::activeEmploymentStatus()
            ->chunk(100, function ($employees) {
                $payrollSummary = [];

                foreach ($employees as $employee) {
                    $payrollSummary[] = $this->storePayrollSummary($employee);
                }

                DB::transaction(fn () => DB::table('payroll_summaries')->insert($payrollSummary), 5);
            });
    }

    private function storePayrollSummary(Employee $employee)
    {
        $psum = $this->payrollSummary;
        $start = $this->payrollSummary->latestPayroll->cut_off_start;
        $end = $this->payrollSummary->latestPayroll->cut_off_end;

        $rawAttendanceLogs = $this->payrollSummary->validateAttendanceLogsPayroll($employee->attendanceLogs, $start, $end);
        $rawOvertimeRecords = $this->payrollSummary->validateOvertimeRecords($employee->overtimes);

        return [
            'employee_id'           => $employee->employee_id,
            'payroll_id'            => $psum->latestPayroll->payroll_id,
            'reg_hrs'               => $psum->getTotalRegHrs($rawAttendanceLogs),
            'reg_nd'                => $psum->getTotalRegNightDiffHrs($rawAttendanceLogs),
            'reg_ot'                => $psum->getTotalRegOtHrs($rawOvertimeRecords),
            'reg_ot_nd'             => $psum->getTotalRegOtNightDiffHrs($rawOvertimeRecords),
            'rest_hrs'              => $psum->getTotalRestHrs($rawAttendanceLogs),
            'rest_nd'               => $psum->getTotalRestNightDiffHrs($rawAttendanceLogs),
            'rest_ot'               => $psum->getTotalRestOtHrs($rawOvertimeRecords),
            'rest_ot_nd'            => $psum->getTotalRestOtNightDiffHrs($rawOvertimeRecords),
            'reg_hol_hrs'           => $psum->getTotalRegHolHrs($rawAttendanceLogs),
            'reg_hol_nd'            => $psum->getTotalRegHolNightDiffHrs($rawAttendanceLogs),
            'reg_hol_ot'            => $psum->getTotalRegHolOtHrs($rawOvertimeRecords),
            'reg_hol_ot_nd'         => $psum->getTotalRegHolOtNightDiffHrs($rawOvertimeRecords),
            'reg_hol_rest_hrs'      => $psum->getTotalRegHolRestHrs($rawAttendanceLogs),
            'reg_hol_rest_nd'       => $psum->getTotalRegHolRestNightDiffHrs($rawAttendanceLogs),
            'reg_hol_rest_ot'       => $psum->getTotalRegHolRestOtHrs($rawOvertimeRecords),
            'reg_hol_rest_ot_nd'    => $psum->getTotalRegHolRestOtNightDiffHrs($rawOvertimeRecords),
            'spe_hol_hrs'           => $psum->getTotalSpeHolHrs($rawAttendanceLogs),
            'spe_hol_nd'            => $psum->getTotalSpeHolNightDiffHrs($rawAttendanceLogs),
            'spe_hol_ot'            => $psum->getTotalSpeHolOtHrs($rawOvertimeRecords),
            'spe_hol_ot_nd'         => $psum->getTotalSpeHolOtNightDiffHrs($rawOvertimeRecords),
            'spe_hol_rest_hrs'      => $psum->getTotalSpeHolRestHrs($rawAttendanceLogs),
            'spe_hol_rest_nd'       => $psum->getTotalSpeHolRestNightDiffHrs($rawAttendanceLogs),
            'spe_hol_rest_ot'       => $psum->getTotalSpeHolRestOtHrs($rawOvertimeRecords),
            'spe_hol_rest_ot_nd'    => $psum->getTotalSpeHolRestOtNightDiffHrs($rawOvertimeRecords),
            'abs_days'              => $psum->getTotalAbsentDays($rawAttendanceLogs),
            'ut_hours'              => $psum->getTotalUtimeHrs($employee),
            'td_hours'              => $psum->getTotalTardyHrs($employee),
            'created_at'            => now(),
            'updated_at'            => now(),
        ];
    }
}
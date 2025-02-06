<?php

namespace App\Console\Commands;

use App\Models\Shift;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\AttendanceLog;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Enums\BiometricPunchType;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class GeneratePayrollSummary extends Command
{
    private $latestPayroll;

    private $regularShift;

    private $nightDifferentialShift;

    public function __construct()
    {
        $this->latestPayroll = Payroll::latest('cut_off_end')->first();

        $this->regularShift = Shift::where('shift_name', 'Regular')->first();

        $this->nightDifferentialShift = Shift::where('shift_name', 'Night Differential')->first();

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-payroll-summary';

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
        $cutOffEnd = Carbon::parse($this->latestPayroll->cut_off_end);

        if (now()->lessThanOrEqualTo($cutOffEnd)) {
            return;
        }

        Log::info("{$cutOffEnd} is greater than or equal today");

        $data = [];

        Employee::activeEmploymentStatus()
            ->get()
            ->each(function ($employee) use (&$data) {
                // Log::info($this->getTotalRegHrs($employee->attendanceLogs));
                // Log::info($this->getTotalRegNightDiffHrs($employee->attendanceLogs));
                // Log::info($this->getTotalRegOtHrs($employee->overtimes));
                // Log::info($this->getTotalRegOtNightDiffHrs($employee->overtimes));
                array_push($data, [
                    'employee_id' => $employee->employee_id,
                    'payroll_id' => $this->latestPayroll->payroll_id,
                    'reg_hrs' => $this->getTotalRegHrs($employee->attendanceLogs),
                    'reg_nd' => $this->getTotalRegNightDiffHrs($employee->attendanceLogs),
                    'reg_ot' => $this->getTotalRegOtHrs($employee->overtimes),
                    'reg_ot_nd' => $this->getTotalRegOtNightDiffHrs($employee->overtimes),
                    'rest_hrs' => $this->getTotalRestHrs($employee->attendanceLogs),
                ]);
            });

        // $test = $this->getTotalRegOtNightDiffHrs(Employee::find(33)->overtimes);
        // Log::info($test);

        // $start = Carbon::createFromFormat('H:i:s', $this->nightDifferentialShift->start_time)->setDateFrom('2025-02-05');
        // $end = Carbon::createFromFormat('H:i:s', $this->nightDifferentialShift->end_time)->setDateFrom('2025-02-05');
            
        // $interval = null;

        // Log::info("before: start = {$start} end = {$end} interval = {$interval}");


        // if ($end->lessThan($start)) {
        //     $end->addDay();
        // }

        // $interval = $start->diffInHours($end);

        // Log::info("after: start = {$start} end = {$end} interval = {$interval}");

        // Log::info('Data to insert: ', $data);

        // db transaction and chunking if need
    }

    private function getTotalRegHrs(Collection $attendanceLogs)
    {
        $validLogs = $attendanceLogs->whereBetween('timestamp', [
                Carbon::parse($this->latestPayroll->cut_off_start)->startOfDay(),
                Carbon::parse($this->latestPayroll->cut_off_end)->endOfDay()
            ])
            ->whereBetween('timestamp', [
                $this->regularShift->start_time,
                $this->regularShift->end_time,
            ])
            ->groupBy('employee_id');

        // Log::info($validLogs);
            
        $diffedHours = $validLogs->map(function ($logs) {
            $dailyHours = 0;
            $checkIn = null;
            
            $sortedLogs = $logs->sortBy('timestamp')->values();

            // Log::info($sortedLogs);
            
            foreach ($sortedLogs as $log) {
                if ($log->type === BiometricPunchType::CHECK_IN->value) {
                    $checkIn = Carbon::parse($log->timestamp);
                }
                elseif ($log->type === BiometricPunchType::CHECK_OUT->value && $checkIn) {
                    $checkOut = Carbon::parse($log->timestamp);
                    $dailyHours += $checkIn->diffInHours($checkOut);

                    $checkIn = null;
                }
            }

            return $dailyHours;
        });

        return round($diffedHours->sum(), 2);
    }

    private function getTotalRegNightDiffHrs(Collection $attendanceLogs)
    {
        $validLogs = $attendanceLogs->whereBetween('timestamp', [
                Carbon::parse($this->latestPayroll->cut_off_start)->startOfDay(),
                Carbon::parse($this->latestPayroll->cut_off_end)->endOfDay()
            ])
            ->whereBetween('timestamp', [
                $this->nightDifferentialShift->start_time,
                $this->nightDifferentialShift->end_time,
            ])
            ->groupBy('employee_id');

        // Log::info($validLogs);
            
        $diffedHours = $validLogs->map(function ($logs) {
            $dailyHours = 0;
            $checkIn = null;
            
            $sortedLogs = $logs->sortBy('timestamp')->values();

            // Log::info($sortedLogs);
            
            foreach ($sortedLogs as $log) {
                if ($log->type === BiometricPunchType::CHECK_IN->value) {
                    $checkIn = Carbon::parse($log->timestamp);
                }
                elseif ($log->type === BiometricPunchType::CHECK_OUT->value && $checkIn) {
                    $checkOut = Carbon::parse($log->timestamp);
                    $dailyHours += $checkIn->diffInHours($checkOut);

                    $checkIn = null;
                }
            }

            return $dailyHours;
        });

        return round($diffedHours->sum(), 2);
    }

    private function getTotalRegOtHrs(Collection $overtimes)
    {
        return $overtimes->filter(function ($ot) {
            $otStart = Carbon::createFromFormat('g:i A', $ot->start_time);

            return 
                $ot->authorizer_signed_at &&
                $otStart->greaterThanOrEqualTo($this->regularShift->start_time) &&
                $otStart->lessThanOrEqualTo($this->regularShift->end_time) &&
                $ot->payrollApproval->payroll_id === $this->latestPayroll->payroll_id;
        })->sum(function ($ot) {
            $start = Carbon::createFromFormat('g:i A', $ot->start_time);
            $end = Carbon::createFromFormat('g:i A', $ot->end_time);

            return $start->diffInHours($end);
        });
    }

    private function getTotalRegOtNightDiffHrs(Collection $overtimes)
    {
        return $overtimes->filter(function ($ot) {
            $otStart = Carbon::createFromFormat('g:i A', $ot->start_time);

            return 
                $ot->authorizer_signed_at &&
                $otStart->greaterThan($this->nightDifferentialShift->start_time) &&
                $ot->payrollApproval->payroll_id === $this->latestPayroll->payroll_id;
        })->sum(function ($ot) {
            $start = Carbon::createFromFormat('g:i A', $ot->start_time)
                        ->setDateFrom($ot->date);
            $end = Carbon::createFromFormat('g:i A', $ot->end_time)
                        ->setDateFrom($ot->date);

            if ($end->lessThan($start)) {
                $end->addDay();
            }

            return $start->diffInHours($end);
        });
    }

    private function getTotalRestHrs(Collection $attendanceLogs)
    {
        //
    }

    private function getTotalRestNightDiffHrs(Collection $attendanceLogs)
    {
        //
    }

    private function getTotalRestOtHrs()
    {
        //
    }

    private function getTotalRestOtNightDiffHrs()
    {
        //
    }

    private function getTotalRegHolHrs()
    {
        //
    }

    private function getTotalRegHolNightDiffHrs()
    {
        //
    }

    private function getTotalRegHolOtHrs()
    {
        //
    }

    private function getTotalRegHolOtNightDiffHrs()
    {
        //
    }

    private function getTotalRegHolRestHrs()
    {
        //
    }

    private function getTotalRegHolRestNightDiffHrs()
    {
        //
    }

    private function getTotalRegHolRestOtHrs()
    {
        //
    }

    private function getTotalRegHolRestOtNightDiffHrs()
    {
        //
    }

    private function getTotalSpeHolHrs()
    {
        //
    }

    private function getTotalSpeHolNightDiffHrs()
    {
        //
    }

    private function getTotalSpeHolOtHrs()
    {
        //
    }

    private function getTotalSpeHolOtNightDiffHrs()
    {
        //
    }

    private function getTotalSpeHolRestHrs()
    {
        //
    }

    private function getTotalSpeHolRestNightDiffHrs()
    {
        //
    }

    private function getTotalSpeHolRestOtHrs()
    {
        //
    }

    private function getTotalSpeHolRestOtNightDiffHrs()
    {
        //
    }

    private function getTotalAbsentDays()
    {
        //   
    }

    private function getTotalUtimeHrs()
    {
        //
    }

    private function getTotalTardyHrs()
    {
        //
    }
}

<?php

namespace App\Console\Commands;

use App\Enums\EmploymentStatus;
use App\Enums\PerformanceEvaluationPeriod;
use App\Models\Employee;
use App\Models\ProbationaryPerformancePeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * - Determine which employees are probationary via the status relationship method
 * - Check their starting date via the lifecycle relationship method
 * - Check if exactly three months have passed. If so, check if employee has existing performance
 * evalaution record via performancesAsProbationary.details relationship. If no, open an evaluation
 * period for the employee starting now via performancesAsProbationary and
 * set start_date attribute value to now() and add 7 days for end_date attribute.
 * - Do the same thing for fifth and final month.
 */
class CheckProbationaryEvaluationPeriodOpening extends Command
{
    private $previousThreeMonths;

    private $previousFiveMonths;

    private $previousSixthMonths;

    public function __construct()
    {
        $this->previousThreeMonths = now()->subMonths(3);
        $this->previousFiveMonths = now()->subMonths(5);
        $this->previousSixthMonths = now()->subMonths(6);

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'probevaluation:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks each probationary employee starting date to know whether to open a performance evaluation period of any of the ff: third, fifth, or final month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->alert('fuck you');

        $probationaries = Employee::whereHas('status', function ($query) {
            $query->where('emp_status_name', EmploymentStatus::PROBATIONARY->label());
        })->get();

        $probationaries->each(function ($probationary) {
            $starting = Carbon::parse($probationary->lifecycle->started_at);

            if ($this->isExactlyOrMoreThanSixthMonthsEmployed($starting) &&
                ! $this->wasEvaluated($probationary, PerformanceEvaluationPeriod::FINAL_MONTH->value)) {
                $this->openEvaluationPeriod($probationary, PerformanceEvaluationPeriod::FINAL_MONTH->value);

            } elseif ($this->isExactlyOrMoreThanFiveMonthsEmployed($starting) &&
                ! $this->wasEvaluated($probationary, PerformanceEvaluationPeriod::FIFTH_MONTH->value)) {
                $this->openEvaluationPeriod($probationary, PerformanceEvaluationPeriod::FIFTH_MONTH->value);

            } elseif ($this->isExactlyOrMoreThanThreeMonthsEmployed($starting) &&
                ! $this->wasEvaluated($probationary, PerformanceEvaluationPeriod::THIRD_MONTH->value)) {
                $this->openEvaluationPeriod($probationary, PerformanceEvaluationPeriod::THIRD_MONTH->value);
            }
        });
    }

    private function openEvaluationPeriod(Employee $probationary, string $period)
    {
        DB::transaction(function () use ($probationary, $period) {
            ProbationaryPerformancePeriod::create([
                'evaluatee' => $probationary->employee_id,
                'period_name' => $period,
                'start_date' => now(),
                'end_date' => now()->addWeek(),
            ]);
        }, 5);
    }

    private function isExactlyOrMoreThanThreeMonthsEmployed(Carbon $starting): bool
    {
        return $starting->lessThanOrEqualTo($this->previousThreeMonths) &&
            ! $starting->lessThanOrEqualTo($this->previousFiveMonths);
    }

    private function isExactlyOrMoreThanFiveMonthsEmployed(Carbon $starting): bool
    {
        return $starting->lessThanOrEqualTo($this->previousFiveMonths) &&
            ! $starting->lessThanOrEqualTo($this->previousSixthMonths);
    }

    private function isExactlyOrMoreThanSixthMonthsEmployed(Carbon $starting): bool
    {
        return $starting->lessThanOrEqualTo($this->previousSixthMonths);
    }

    private function wasEvaluated(Employee $employee, string $period)
    {
        $finalEvaluation = $employee->performancesAsProbationary
            ->filter(function ($item) use ($period) {
                return $item->period_name === $period;
            }
            );

        return $finalEvaluation->isNotEmpty();
    }
}

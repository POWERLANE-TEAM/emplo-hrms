<?php

namespace App\Console\Commands;

use App\Services\ServiceIncentiveLeaveCreditService;
use Illuminate\Console\Command;

class IncreaseServiceIncentiveLeaveCredits extends Command
{
    public function __construct(private ServiceIncentiveLeaveCreditService $silCreditService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'silcredits:increase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increase sil credits of each employee who are more than 1 year into service.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $activeEmployees = $this->silCreditService->getActiveEmployees();

        $experiencedEmployees = $this->silCreditService->filterExperiencedEmployees($activeEmployees);

        if ($experiencedEmployees->isEmpty()) {
            return;
        }

        $hiredThisMonthAndDay = $this->silCreditService->filterHiredThisMonthAndDay($experiencedEmployees);

        if ($hiredThisMonthAndDay->isEmpty()) {
            return;
        }

        $silCredits = [];

        $hiredThisMonthAndDay->each(function ($employee) use (&$silCredits) {
            $yrsInService = $this->silCreditService->getServiceDuration($employee->jobDetail->hired_at);

            $total = $this->silCreditService->getTotalIncreaseSilCredits($employee, $yrsInService);

            $silCredits[] = [
                'employee_id' => $employee->employee_id,
                'vacation_leave_credits' => $total->vlCredits,
                'sick_leave_credits' => $total->slCredits,
            ];
        });

        $this->silCreditService->save($silCredits);
    }
}

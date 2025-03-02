<?php

namespace App\Console\Commands;

use App\Services\ServiceIncentiveLeaveCreditService;
use Illuminate\Console\Command;

class ResetServiceIncentiveLeaveCredits extends Command
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
    protected $signature = 'silcredits:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset each active employee\'s service incentive leave (sick and vacation) credits.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $silCredits = [];

        $activeEmployees = $this->silCreditService->getActiveEmployees();

        $activeEmployees->each(function ($employee) use (&$silCredits) {
            $serviceDuration = $this->silCreditService->getServiceDuration($employee->jobDetail->hired_at);

            $credit = $this->silCreditService->resetSilCredits($serviceDuration);

            $silCredits[] = [
                'employee_id' => $employee->employee_id,
                'sick_leave_credits' => $credit,
                'vacation_leave_credits' => $credit,
            ];
        });

        $this->silCreditService->save($silCredits);
    }
}

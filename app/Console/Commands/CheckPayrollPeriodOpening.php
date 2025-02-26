<?php

namespace App\Console\Commands;

use App\Enums\Payroll;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Models\Payroll as PayrollModel;

class CheckPayrollPeriodOpening extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prollperiod:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a payroll period open for today.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $latestRecord = PayrollModel::latest('cut_off_start')->first();
        $today = Payroll::getCutOffPeriod(now());

        if (! $latestRecord) {
            PayrollModel::create([
                'cut_off_start' => $today['start'],
                'cut_off_end' => $today['end'],
                'payout' => Payroll::getPayoutDate($today['start'])
            ]);
            return;
        }

        $cutOffStart = Carbon::parse($latestRecord->cut_off_start);

        if ($cutOffStart->lessThan($today['start'])) {
            PayrollModel::create([
                'cut_off_start' => $today['start'],
                'cut_off_end' => $today['end'],
                'payout' => Payroll::getPayoutDate($today['start'])
            ]);
            return;
        }
    }
}

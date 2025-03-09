<?php

namespace App\Console\Commands;

use App\Enums\PerformanceEvaluationPeriod;
use App\Models\RegularPerformancePeriod;
use Illuminate\Console\Command;

class OpenRegularEvaluationPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regevaluation:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open regular evaluation period annually.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $latest = RegularPerformancePeriod::select('start_date')->latest('start_date')->first();

        if ($latest->start_date->year < now()->year) {
            RegularPerformancePeriod::create([
                'period_name' => PerformanceEvaluationPeriod::ANNUAL->value,
                'start_date' => now(),
                'end_date' => now()->addWeeks(2),
            ]);
        }

    }
}

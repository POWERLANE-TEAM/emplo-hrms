<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckProbationaryEvaluationPeriodOpening extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-probationary-evaluation-period-opening';

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
        // - get all employee models, implement chunking if needed
        // - determine which employees are probationary via the status relationship method
        // - check their starting date via the lifecycle method
        // - check if exactly three months have passed. If so, check if employee has existing performance
        // evalaution record via performancesAsProbationary.details relationship.
        // - if no, open an evaluation period for the employee starting now via performancesAsProbationary and 
        // set start_date attribute value to now() and add 7 days for end_date attribute.
        // - do the same thing for fifth and final month.
    }
}

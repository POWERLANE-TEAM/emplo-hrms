<?php

namespace App\Console\Commands;

use App\Enums\EmploymentStatus;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteSeparatedEmployeeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete separated employee data which separation date is exactly or exceeds 4 years.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        activity()->disableLogging();

        Employee::inactiveEmploymentStatus()
            ->each(function ($employee) {
                $separationDate = Carbon::parse($employee->lifecycle->separated_at);
                $retentionPeriod = EmploymentStatus::separatedEmployeeDataRetentionPeriod($separationDate);

                if (now()->greaterThanOrEqualTo($retentionPeriod)) {
                    $employee->account()->forceDelete();
                    $employee->trainings()->delete();
                    $employee->delete();
                }
            }
            );

        activity()->enableLogging();
    }
}

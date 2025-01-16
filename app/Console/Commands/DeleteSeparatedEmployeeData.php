<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use Illuminate\Console\Command;

class DeleteSeparatedEmployeeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-separated-employee-data';

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

        $separatedEmployees = Employee::query()
            ->whereHas('status', function ($query) {
                $query->whereNotIn('emp_status_name', [
                    EmploymentStatus::PROBATIONARY->label(),
                    EmploymentStatus::REGULAR->label(),
                ]);
            }
        );

        $separatedEmployees->each(function ($employee) {
            $separationDate = Carbon::parse($employee->lifecycle->separated_at);
            $retentionPeriod = EmploymentStatus::separatedEmployeeDataRetentionPeriod($separationDate);
            
            if (now()->greaterThanOrEqualTo($retentionPeriod)) {
                $employee->account()->forceDelete();
                $employee->trainings()->delete();
                $employee->delete();
            }
        });

        activity()->enableLogging();
    }
}

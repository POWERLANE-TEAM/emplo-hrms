<?php

use Illuminate\Support\Facades\Schedule;

/**
 * To-dos
 * 
 * Command that execute every January 1 to reset the leave balance of every employee to 10(default).
 * Command that clears the activity log. I don't know if this should be set annually to execute.
 * Command that executes daily to backup the database.
 * Command that generates the payroll summary for payroll_summaries table
 * Command that executes daily to remove applicants who are rejected for more or exactly than 30 days.
 */

Schedule::daily()
    ->timezone('Asia/Manila')
    ->group(function () {
        Schedule::command('sync-attlogs-from-biometric-device'); // to revisit implementation
        Schedule::command('check-probationary-evaluation-period-opening');
        Schedule::command('check-payroll-period-opening');
        Schedule::command('delete-separated-employee-data');
        Schedule::command('generate-payroll-summary');
    }
);

// Schedule::command('generate-payroll-summary')
//     ->timezone('Asia/Manila')
//     ->everyFiveSeconds();
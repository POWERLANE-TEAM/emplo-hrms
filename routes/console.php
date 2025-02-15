<?php

use Illuminate\Support\Facades\Schedule;

/**
 * To-dos
 * 
 * Command that execute every January 1 to reset the leave balance of every employee to 10(default).
 * Command that executes daily to remove applicants who are rejected for more or exactly than 30 days.
 */

Schedule::daily()
    ->withoutOverlapping()
    ->timezone('Asia/Manila')
    ->group(function () {
        Schedule::command('sync-attlogs-from-biometric-device');
        Schedule::command('check-probationary-evaluation-period-opening');
        Schedule::command('check-payroll-period-opening');
        Schedule::command('delete-separated-employee-data');
        Schedule::command('generate-payroll-summary');
        Schedule::command('activitylog:clean');
        Schedule::command('backup:clean');
        Schedule::command('backup:run --only-db');
    }
);

// for debugging
// Schedule::command('backup:clean')->everyFiveSeconds();
// Schedule::command('backup:run --only-db')->everyFiveSeconds();
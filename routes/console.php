<?php

use Illuminate\Support\Facades\Schedule;

/**
 * To-dos
 * 
 * Command that execute every January 1 to reset the leave balance of every employee to 10(default).
 */

Schedule::withoutOverlapping()
    ->timezone('Asia/Manila')
    ->group(function () {

        /** Daily cron */
        Schedule::daily()->group(function () {
            Schedule::command('sync-attlogs-from-biometric-device');
            Schedule::command('check-probationary-evaluation-period-opening');
            Schedule::command('check-payroll-period-opening');
            Schedule::command('delete-separated-employee-data');
            Schedule::command('delete-rejected-applicants-data');
            Schedule::command('generate-payroll-summary');
            Schedule::command('activitylog:clean');
            Schedule::command('backup:clean');
            Schedule::command('backup:run --only-db');            
        });

        Schedule::command('open-regular-evaluation-period')->yearly();
    }
);

// for debugging
// Schedule::command('backup:clean')->everyFiveSeconds();
// Schedule::command('backup:run --only-db')->everyFiveSeconds();
// Schedule::command('open-regular-evaluation-period')->everyFiveSeconds();
// Schedule::command('delete-rejected-applicants-data')->everyFiveSeconds();
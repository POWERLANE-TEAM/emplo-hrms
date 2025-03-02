<?php

use Illuminate\Support\Facades\Schedule;

Schedule::timezone('Asia/Manila')->group(function () {
    /** Daily cron */
    Schedule::daily()->group(function () {
        Schedule::command('attlogs:sync');
        Schedule::command('probevaluation:open');
        Schedule::command('prollperiod:open');
        Schedule::command('employee:clean');
        Schedule::command('applicant:clean');
        Schedule::command('activitylog:clean');

        Schedule::withoutOverlapping()->group(function () {
            Schedule::command('silcredits:increase');
            Schedule::command('prollsummary:generate');
            Schedule::command('backup:clean');
            Schedule::command('backup:run --only-db');   
        });
    });

    /** Yearly (Jan 1) cron */
    Schedule::yearly()->group(function () {
        Schedule::command('silcredits:reset');
        Schedule::command('regevaluation:open');
    });
});

// for debugging
// Schedule::command('silcredits:reset')->everyFiveSeconds();
// Schedule::command('silcredits:increase')->everyTenSeconds();

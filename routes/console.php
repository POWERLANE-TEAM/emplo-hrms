<?php

use Illuminate\Support\Facades\Schedule;

/**
 * To-dos
 * 
 * Command that execute every January 1 to reset the leave balance of every employee to 10(default).
 * Command that clears the activity log. I don't know if this should be set annually to execute.
 * Command that executes daily which checks and permanently deletes employee records who are already separated for 5 years.
 * Command that executes daily to backup the database.
 * Command that generates the payroll summary for payroll_summaries table
 * Command that executes daily to remove applicants who are rejected for more or exactly than 30 days.
 * 
 * Tip: replace frequency with short intervals like every(x)seconds for debugging
 */

Schedule::command('sync-attlogs-from-biometric-device')
    ->timezone('Asia/Manila')
    ->daily();

Schedule::command('check-probationary-evaluation-period-opening')
    ->timezone('Asia/Manila')
    ->daily();

Schedule::command('check-payroll-period-opening')
    ->timezone('Asia/Manila')
    ->daily();
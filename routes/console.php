<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sync-attlogs-from-biometric-device')
    ->timezone('Asia/Manila')
    ->daily();

// for testing stuff
// Schedule::command('check-probationary-evaluation-period-opening')
//     ->timezone('Asia/Manila')
//     ->everyFiveSeconds();

Schedule::command('check-probationary-evaluation-period-opening')
    ->timezone('Asia/Manila')
    ->daily();
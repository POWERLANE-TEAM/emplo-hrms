<?php

use Illuminate\Support\Facades\Schedule;

// replace frequency with short intervals like every(x)seconds for debugging

Schedule::command('sync-attlogs-from-biometric-device')
    ->timezone('Asia/Manila')
    ->daily();

Schedule::command('check-probationary-evaluation-period-opening')
    ->timezone('Asia/Manila')
    ->daily();
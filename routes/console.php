<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sync-attlogs-from-biometric-device')
    ->timezone('Asia/Manila')
    ->daily();
<?php

Schedule::command('sync-attlogs-from-biometric-device')
    ->timezone('Asia/Manila')
    ->daily();
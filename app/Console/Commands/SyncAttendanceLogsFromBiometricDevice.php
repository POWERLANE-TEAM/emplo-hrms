<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Illuminate\Console\Command;

class SyncAttendanceLogsFromBiometricDevice extends Command
{
    public function __construct(private AttendanceService $attendanceService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-attlogs-from-biometric-device';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get attendance logs from ZKTeco device and persist them to the attendance_logs table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->attendanceService->storeDtrLogs();
        // $this->attendanceService->clearDtrLogs();
    }
}

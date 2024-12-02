<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use App\Enums\ActivityLogName;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\BiometricDevice;

class SyncAttendanceLogsFromBiometricDevice extends Command
{
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
        DB::transaction(function () {
            $zk = new BiometricDevice();
            $logs = $zk->getRawAttendanceLogs()->map(function ($log) {
                return AttendanceLog::create([
                        'uid' => $log->uid,
                        'employee_id' => (int) $log->id,
                        'state' => $log->state,
                        'type' => $log->type,
                        'timestamp' => $log->timestamp,
                    ]);
            });
            
            activity()
                ->useLog(ActivityLogName::SYSTEM->value)
                ->withProperties($logs)
                ->event('created')
                ->log(__('Save attendance logs to database from biometric attendance machine.'));
        }, 3);
    }
}

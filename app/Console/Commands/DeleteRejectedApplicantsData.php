<?php

namespace App\Console\Commands;

use App\Enums\AccountType;
use App\Models\Application;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteRejectedApplicantsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicant:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete applicants with their data who were rejected for more or exactly 30 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Application::query()
            ->select('applicant_id')
            ->rejectedDuration(30)
            ->chunk(100, function ($ids) {
                $ids = $ids->toArray();

                DB::transaction(function () use ($ids) {
                    DB::table('users')
                        ->where('account_type', AccountType::APPLICANT->value)
                        ->whereIn('account_id', $ids)
                        ->delete();

                    DB::table('applicants')
                        ->whereIn('applicant_id', $ids)
                        ->delete();
                }, 5);
            });
    }
}

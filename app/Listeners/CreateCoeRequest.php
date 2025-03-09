<?php

namespace App\Listeners;

use App\Events\ResignationApproved;
use App\Models\CoeRequest;
use Illuminate\Support\Facades\Log;

class CreateCoeRequest
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ResignationApproved $event): void
    {
        // Log::info('Create COE Request' . $event);
        CoeRequest::create([
            'requested_by' => $event->employee->employee_id,
        ]);
    }
}

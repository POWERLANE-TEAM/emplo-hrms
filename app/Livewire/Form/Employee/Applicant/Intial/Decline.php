<?php

namespace App\Livewire\Form\Employee\Applicant\Intial;

use App\Enums\ApplicationStatus;
use App\Enums\UserPermission;
use App\Http\Controllers\Application\ApplicationController;
use App\Models\Application;
use App\Notifications\applicant\ResumeRejected;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use PhpParser\Node\Stmt\TryCatch;

class Decline extends Component
{


    public Application $application;

    public $applicationStatusId;

    public function mount(Application $application)
    {
        $this->application = $application;
    }

    public function store()
    {

        if (! auth()->user()->hasPermissionTo(UserPermission::UPDATE_APPLICATION_STATUS)) {
            abort(403);
        }

        $this->application->load(['vacancy.jobTitle']);

        $update['applicationId'] = $this->application->application_id;
        $update['applicationStatusId'] = ApplicationStatus::REJECTED->value;

        $controller = new ApplicationController();

        $controller->update($update, true);

        $user = $this->application->applicant->account;

        $notification = new ResumeRejected(
            ['email' => env('MAIL_FROM_ADDRESS')],
            $this->application
        );

        $user->notify($notification);
    }


    public function render()
    {
        return view('livewire.form.employee.applicant.intial.decline');
    }
}

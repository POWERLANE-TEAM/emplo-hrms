<?php

namespace App\Livewire\Employee;

use App\Enums\UserRole;
use App\Http\Controllers\PreEmploymentController;
use App\Models\PreempRequirement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class PreEmploymentDoc extends Component
{
    use WithFileUploads;

    public PreempRequirement $pre_employment_req;

    public User $applicant;

    const MAX_FILE_SIZE = 5 * 1024;

    /*  When global validation of livewire is triggered the attribute specified in :as is not respected */
    #[Validate("mimes:pdf|max:" . self::MAX_FILE_SIZE, as: "Preemployment File")]
    public $preemp_file;

    public function mount()
    {
        $authenticated_user = Auth::guard()->user();

        $this->applicant = User::where('user_id', $authenticated_user->user_id)
            ->with('roles')
            ->first();
    }

    public function save(Request $request, PreEmploymentController $controller)
    {
        // check permission instead of role
        if (!$this->applicant->hasRole([UserRole::BASIC])) {
            abort(403);
        }

        try {
            $this->validate();

            $request = new Request();
            $request->files->set('pre_emp_doc', $this->preemp_file);

            $request->merge([
                'doc_id' => $this->pre_employment_req->preemp_req_id
            ]);

            $controller->store($request);
        } catch (ValidationException $e) {

            $error_message = $this->getErrorBag();
            $preemp_file_errors = $error_message->get('preemp_file');

            $this->dispatch("{$this->__id}:uploadError", ['docId' => $this->pre_employment_req->preemp_req_id, 'message' => $preemp_file_errors[0]]);

            return;
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <tr class="opacity-0" aria-live="polite" aria-label="Loading more documents">
            <td colspan="4" class="text-center">
                Loading more items
            </td>
        </tr>
        HTML;
    }

    public function render()
    {

        return view('livewire.employee.pre-employment-doc');
    }
}

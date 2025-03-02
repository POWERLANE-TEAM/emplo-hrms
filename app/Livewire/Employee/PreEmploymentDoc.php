<?php

namespace App\Livewire\Employee;

use App\Enums\UserPermission;
use App\Http\Controllers\ApplicationDocController;
use App\Models\PreempRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class PreEmploymentDoc extends Component
{
    use WithFileUploads;

    public PreempRequirement $pre_employment_req;

    public $file;

    const MAX_FILE_SIZE = 5 * 1024;

    /*  When global validation of livewire is triggered the attribute specified in :as is not respected */
    #[Validate('mimes:pdf|max:'.self::MAX_FILE_SIZE, as: 'Preemployment File')]
    public $preemp_file;

    public function mount()
    {
        try {
            $application = auth()->user()->account->application;

            $filePath = optional($application->documents()
                ->where('preemp_req_id', $this->pre_employment_req->preemp_req_id)
                ->latest()
                ->first())
                ->file_path;

            if ($filePath) {
                $this->file = Storage::disk('public')->url($filePath);
            }
        } catch (\Throwable $th) {
            report($th);
        }

    }

    public function save(Request $request, ApplicationDocController $controller)
    {

        $user = Auth::user();

        // if (! $user->hasAllPermissions([UserPermission::CREATE_PRE_EMPLOYMENT_DOCUMENT])) {
        //     abort(403, 'You are not authorized to perform this action yet');
        // }

        try {
            $this->validate();

            $request = new Request;
            $request->files->set('applicationDoc', $this->preemp_file);

            $request->merge([
                'doc_id' => $this->pre_employment_req->preemp_req_id,
            ]);

            $controller->store($request);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'File upload successfully',
            ]);

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

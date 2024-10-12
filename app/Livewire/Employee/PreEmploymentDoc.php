<?php

namespace App\Livewire\Employee;

use App\Models\PreempRequirement;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class PreEmploymentDoc extends Component
{
    use WithFileUploads;

    #[Modelable]
    public PreempRequirement $pre_employment_req;

    /*  When global validation of livewire is triggered the attribute specified in :as is not respected */
    #[Validate('mimes:pdf|max:480', as: "Preemployment File")]
    public $preemp_file;

    public function save()
    {

        try {
            $this->validate();
        } catch (ValidationException $e) {

            $error_message = $this->getErrorBag();
            $preemp_file_errors = $error_message->get('preemp_file');

            // dump($preemp_file_errors);
            $this->dispatch("{$this->__id}:uploadError", ['docId' => $this->pre_employment_req->preemp_req_id, 'message' => $preemp_file_errors[0]]);

            return;
        }

        // $this->preemp_file->store('pre-employment-docs', 'public');
        $originalName = $this->preemp_file->getClientOriginalName();
        dump($originalName);
        dump($this->preemp_file);
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

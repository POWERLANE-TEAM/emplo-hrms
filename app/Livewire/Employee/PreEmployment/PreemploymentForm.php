<?php

namespace App\Livewire\Employee\PreEmployment;

use App\Enums\UserRole;
use App\Http\Controllers\ApplicationDocController;
use App\Http\Helpers\FilepondHelper;
use App\Models\ApplicationDoc;
use App\Models\Employee;
use App\Models\PreempRequirement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class PreemploymentForm extends Component
{
    use WithFilePond;

    public Employee $employee;

    #[Locked]
    private bool $isBasicEmployee;

    public Collection $preemploymentReqs;

    public ApplicationDoc $applicationDocs;

    public $preemploymentDocs;

    public function mount()
    {
        $this->setPreemploymentDocs();
    }

    public function boot()
    {
        try {
            $this->isBasicEmployee = auth()->user()->hasRole(UserRole::BASIC);

            $this->employee->loadMissing(['jobDetail']);

            $this->preemploymentReqs = PreempRequirement::whereNot('preemp_req_id', 17)->with(['applicationDocs' => function ($query) {
                $query->where('application_id', $this->employee->jobDetail->application_id);
            }])->get();

        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function setPreemploymentDocs()
    {
        $this->preemploymentDocs = $this->preemploymentReqs->map(function ($req) {
            $docArray = [
                'preemp_req_id' => $req->preemp_req_id,
                'file' => Storage::disk('public')->url('/'.optional(optional($req->applicationDocs)->first())->file_path ?? ''),
            ];

            $docArray['oldFile'] = $docArray['file'] ? $docArray['file'] : null;

            return $docArray;
        });
    }

    public function render()
    {
        return view('livewire.employee.pre-employment.preemployment-form');
    }

    public function save($docId)
    {

        $user = auth()->user();

        $fileFromLivewire = FilepondHelper::transfromToFile($this->preemploymentDocs[$docId]['file']->getRealPath());

        // if (! $user->hasAllPermissions([UserPermission::CREATE_PRE_EMPLOYMENT_DOCUMENT])) {
        //     abort(403, 'You are not authorized to perform this action yet');
        // }

        try {
            // $this->validate();

            $controller = new ApplicationDocController;

            $request = new Request;
            $request->files->set('applicationDoc', $fileFromLivewire);

            $request->merge([
                'doc_id' => $this->preemploymentDocs[$docId]['preemp_req_id'],
            ]);

            $controller->store($request);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'File upload successfully',
            ]);

        } catch (ValidationException $e) {

            $this->dispatch('show-toast', [
                'type' => 'danger',
                'message' => 'File upload failed',
            ]);

            return;
        }
    }
}

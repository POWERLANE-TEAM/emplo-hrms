<?php

namespace App\Livewire\Employee\Documents\Application;

use App\Models\ApplicationDoc;
use Livewire\Component;

class UploadManager extends Component
{

    public ApplicationDoc $EmpApplicationDocs;

    // TODO: add making of modal containing form for file input

    public function render()
    {
        return view('livewire.employee.documents.application.upload-manager');
    }
}

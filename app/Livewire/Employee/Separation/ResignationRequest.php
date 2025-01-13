<?php

namespace App\Livewire\Employee\Separation;

use App\Http\Controllers\Separation\ResignationController;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class ResignationRequest extends Component
{
    use WithFilePond;

    public $resignationFile;

    #[On('file-resignation')]
    public function save(ResignationController $controller){

        $validated = $this->validate([
            'resignationFile' => 'required'
        ]);

        $controller->store($validated, true);

        $this->redirect(route('employee.separation.index'), navigate: true);

    }

    public function render()
    {
        return view('livewire.employee.separation.resignation-request');
    }
}

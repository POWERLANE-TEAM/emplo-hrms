<?php

namespace App\Livewire\Employee\Separation;

use App\Enums\FilePath;
use App\Models\EmployeeDoc;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Resignation extends Component
{
    #[Locked]
    public bool $hasResignation;

    public EmployeeDoc $resignation;

    public function mount()
    {
        $this->hasResignation = auth()->user()->account->documents()->where('file_path', 'like', '%'.FilePath::RESIGNATION->value.'%')->exists();

        if ($this->hasResignation) {
            auth()->user()->loadMissing('account.documents');
            $this->resignation = auth()->user()->account->documents()->where('file_path', 'like', '%'.FilePath::RESIGNATION->value.'%')->first();
        }
    }

    public function render()
    {
        return view('livewire.employee.separation.resignation');
    }
}

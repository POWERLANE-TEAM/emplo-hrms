<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Document;

class PreEmployment extends Component
{
    public $pre_employment_docs;
    public $loads = 0;
    public $chunk = 5;
    public $additional_docs;

    public function loadMore()
    {
        $this->loads++;
    }

    public function render()
    {
        // $this->pre_employment_docs = Document::take($this->chunk)->get();
        $docs = Document::offset($this->chunk * $this->loads)->limit($this->chunk)->get();
        $this->pre_employment_docs = ($this->loads == 0) ? $docs : $this->pre_employment_docs->merge($docs);

        // if (empty($this->pre_employment_docs)) {
        //     return;
        // }

        return view('livewire.employee.pre-employment', ['pre_employment_docs' => $this->pre_employment_docs]);
    }

    public function rendered()
    {
        $this->dispatch('pre-employment-docs-rendered');
    }
}

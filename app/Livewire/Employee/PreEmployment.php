<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Document;

class PreEmployment extends Component
{
    protected $pre_employment_docs;
    public $loads = 0;
    public $chunk = 5;
    public $all_docs;

    public function loadMore()
    {
        $this->chunk += 5;
        // $this->loads++;
    }

    public function render()
    {
        $this->pre_employment_docs = Document::take($this->chunk)->get();

        // if (empty($this->pre_employment_docs)) {
        //     return;
        // }

        return view('livewire.employee.pre-employment', ['pre_employment_docs' => $this->pre_employment_docs]);
    }
}

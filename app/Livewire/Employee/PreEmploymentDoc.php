<?php

namespace App\Livewire\Employee;

use Livewire\Component;

class PreEmploymentDoc extends Component
{
    public $pre_employment_doc;

    // public function mount($pre_employment_doc)
    // {
    //     $this->pre_employment_doc = $pre_employment_doc;
    // }

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

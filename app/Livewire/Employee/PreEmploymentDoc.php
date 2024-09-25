<?php

namespace App\Livewire\Employee;

use Livewire\Component;

class PreEmploymentDoc extends Component
{
    public $pre_employment_req;

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

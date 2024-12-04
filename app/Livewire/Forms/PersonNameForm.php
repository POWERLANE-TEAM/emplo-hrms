<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PersonNameForm extends Form
{
    #[Validate([
        'firstName' => 'required|string|min:2|max:255'
    ], as: 'first name')]
    public $firstName;

    #[Validate([
        'middleName' => 'nullable|bail|string|min:2|max:255',
    ], as: 'middle name')]
    public $middleName;

    #[Validate([
        'middleInitals' => 'nullable|bail|string|max:255',
    ], as: 'middle initial(s)')]
    public $middleInitals;

    #[Validate([
        'lastName' => 'required|string|min:2|max:255',
    ], as: 'last name')]
    public $lastName;

    #[Validate([
        'prefix' => 'nullable|bail|string|min:2|max:50',
    ], as: 'prefix')]
    public $prefix;

    #[Validate([
        'suffix' => 'nullable|bail|string|min:2|max:50',
    ], as: 'suffix')]
    public $suffix;
}

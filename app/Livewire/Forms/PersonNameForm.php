<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PersonNameForm extends Form
{
    /**
     * Validation rules for the first name field.
     *
     * - regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/:
     *   - Must not start with a space.
     *   - Must not contain consecutive spaces.
     *   - Must not end with a space.
     *   - Can only contain letters (including Ññ), spaces, and apostrophes.
     *
     */
    #[Validate([
        'firstName' => 'required|string|min:2|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/'
    ], as: 'first name')]
    public $firstName;

    /**
     * Validation rules for the middle name field.
     *
     * - regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/:
     *   - Must not start with a space.
     *   - Must not contain consecutive spaces.
     *   - Must not end with a space.
     *   - Can only contain letters (including Ññ), spaces, and apostrophes.
     *
     */
    #[Validate([
        'middleName' => 'nullable|string|min:2|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/',
    ], as: 'middle name')]
    public $middleName;

    #[Validate([
        'middleInitals' => 'nullable|string|max:255',
    ], as: 'middle initial(s)')]
    public $middleInitals;

    /**
     * Validation rules for the last name field.
     *
     * - 'regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \'\\-]+$/':
     *   The field must match the specified regular expression:
     *   - Must not start with a space.
     *   - Must not contain consecutive spaces.
     *   - Must not end with a space.
     *   - Can contain letters (including Ñ and ñ), spaces, apostrophes, and hyphens.
     *
     */
    #[Validate([
        'lastName' => 'required|string|min:2|max:255||regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \'\\-]+$/',
    ], as: 'last name')]
    public $lastName;

    #[Validate([
        'prefix' => 'nullable|string|min:2|max:50',
    ], as: 'prefix')]
    public $prefix;

    #[Validate([
        'suffix' => 'nullable|string|min:2|max:50',
    ], as: 'suffix')]
    public $suffix;
}

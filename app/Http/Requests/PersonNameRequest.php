<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonNameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function rules()
    {
        return [
            'firstName' => 'required|string|min:2|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/',
            'middleName' => 'nullable|string|min:2|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/',
            'middleInitals' => 'nullable|string|max:10',
            'lastName' => 'required|string|min:2|max:255|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \'\\-]+$/',
            'prefix' => 'nullable|string|min:2|max:50',
            'suffix' => 'nullable|string|min:2|max:50',
        ];
    }

    public function attributes()
    {
        return [
            'firstName' => 'first name',
            'middleName' => 'middle name',
            'middleInitals' => 'middle initial(s)',
            'lastName' => 'last name',
            'prefix' => 'prefix',
            'suffix' => 'suffix',
        ];
    }
}

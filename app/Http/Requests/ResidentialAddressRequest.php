<?php

namespace App\Http\Requests;

use App\Rules\Address\AddressRule;
use App\Rules\Address\BarangayRule;
use App\Rules\Address\CityRule;
use App\Rules\Address\ProvinceRule;
use App\Rules\Address\RegionRule;
use Illuminate\Foundation\Http\FormRequest;

class ResidentialAddressRequest extends FormRequest
{

    protected bool $required;


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function rules(bool $required = true)
    {
        $this->required = $required;

        return [
            'region' => 'bail|' . (new  RegionRule($this->required))->getRule(),
            'province' => 'bail|' . (new  ProvinceRule($this->required))->getRule(),
            'city' => 'bail|' . (new  CityRule($this->required))->getRule(),
            'barangay' => 'bail|' . (new  BarangayRule($this->required))->getRule(),
            'address' => 'bail|' . (new  AddressRule($this->required))->getRule(),
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

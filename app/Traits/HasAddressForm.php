<?php

namespace App\Traits;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;

trait HasAddressForm
{


    public $address = [
        // Present Address
        'presentRegion' => null,
        'presentProvince' =>  null,
        'presentCity' =>  null,
        'presentBarangay' =>  null,
        'presentAddress' =>  null,

        // Permanent Address
        'permanentRegion' => null,
        'permanentProvince' => null,
        'permanentCity' => null,
        'permanentBarangay' => null,
        'permanentAddress' => null,
    ];

    /**
     * Populate addresses for present regions, provinces, cities, and barangays.
     *
     * @var array<string, int>
     */
    public $presentAddressFields = [
        'regions' => [],
        'provinces' => [],
        'cities' => [],
        'barangays' => [],
    ];

    /**
     * Populate addresses for permanent regions, provinces, cities, and barangays.
     *
     * @var array<string, int, null>
     */
    public $permanentAddressFields = [
        'regions' => [],
        'provinces' => [],
        'cities' => [],
        'barangays' => [],
    ];

    /**
     * Set the initial state/code of region and province.
     *
     * I would assume that most of the applicants are based within the
     * Region IV-A Calabarzon (04), specifically in Laguna (04034).
     *
     * @var array<string>
     */
    private $initialAddressState = [
        'region' => '04',
        'province' => '04034',
    ];

    /**
     * Should show if required present fields have values.
     *
     * @var array<bool>
     */
    public $samePresentAddressChckBox = [
        'shown' => false,
        'checked' => false,
    ];


    public function updatingAddress($property, $value = null)
    {
        if ($this->samePresentAddressChckBox['checked']) {

            $this->address['permanentRegion'] ??= $this->address['presentRegion'];
            $this->address['permanentProvince'] ??= $this->address['presentProvince'];
            $this->address['permanentCity'] ??= $this->address['presentCity'];
            $this->address['permanentBarangay'] ??= $this->address['presentBarangay'];

            if (in_array($property, $this->permanentAddressProperties())) {
                $presentProperty = str_replace('permanent', 'present', $property);
                $presentProperty = explode('.', $presentProperty)[1];

                $permanentProperty = explode('.', $property)[1];

                $this->addError('samePresentAddressChckBox.checked', 'You set the permanent address to be the same as the present address.');
            }
        } else {
            $this->resetErrorBag('samePresentAddressChckBox.checked');
        }
    }


    /**
     * Handle automatic dropdown options for provinces, cities, and barangays.
     *
     * @param  mixed  $property
     * @return void
     */
    public function updatedAddress($property, $value = null)
    {

        Log::info('Updated Address: ' . $property . ' ' . $value);
        if ($property === 'address.presentRegion') {
            $this->presentAddressFields['provinces'] = Province::where('region_code', $this->address['presentRegion'])
                ->pluck('name', 'province_code')
                ->toArray();

            if ($this->address['presentRegion'] === '13') {
                $this->presentAddressFields['cities'] = City::where('region_code', $this->address['presentRegion'])
                    ->pluck('name', 'city_code')
                    ->toArray();
            }

            $this->resetRelatedAddressFields($property, $this->presentAddressProperties(false));
        }
        if ($property === 'address.permanentRegion') {
            $this->permanentAddressFields['provinces'] = Province::where('region_code', $this->address['permanentRegion'])
                ->pluck('name', 'province_code')
                ->toArray();

            if ($this->address['permanentRegion'] === '13') {
                $this->permanentAddressFields['cities'] = City::where('region_code', $this->address['permanentRegion'])
                    ->pluck('name', 'city_code')
                    ->toArray();
            }

            $this->resetRelatedAddressFields($property, $this->permanentAddressProperties(false));
        }
        if ($property === 'address.presentProvince') {
            $this->presentAddressFields['cities'] = City::where('province_code', $this->address['presentProvince'])
                ->pluck('name', 'city_code')
                ->toArray();

            $this->resetRelatedAddressFields($property, $this->presentAddressProperties(false));
        }
        if ($property === 'address.permanentProvince') {
            $this->permanentAddressFields['cities'] = City::where('province_code', $this->address['permanentProvince'])
                ->pluck('name', 'city_code')
                ->toArray();

            $this->resetRelatedAddressFields($property, $this->permanentAddressProperties(false));
        }
        if ($property === 'address.presentCity') {
            $this->presentAddressFields['barangays'] = Barangay::where('city_code', $this->address['presentCity'])
                ->pluck('name', 'id')
                ->toArray();

            $this->resetRelatedAddressFields($property, $this->presentAddressProperties(false));
        }
        if ($property === 'address.permanentCity') {
            $this->permanentAddressFields['barangays'] = Barangay::where('city_code', $this->address['permanentCity'])
                ->pluck('name', 'id')
                ->toArray();

            $this->resetRelatedAddressFields($property, $this->permanentAddressProperties(false));
        }

        isset(
            $this->address['presentRegion'],
            $this->address['presentCity'],
            $this->address['presentBarangay'],
            $this->address['presentAddress']
        )
            ? $this->samePresentAddressChckBox['shown'] = true
            : $this->samePresentAddressChckBox['shown'] = false;

        // This shoudld prevent chaging premanent address if same as present is checked
        $this->useSameAsPresentAddress(false);
    }

    protected function resetRelatedAddressFields($property, $properties)
    {
        $startClearing = false;

        foreach ($properties as $prop) {
            if ($startClearing) {
                $key = explode('.', $prop)[1];
                $this->address[$key] = null;
            }

            if ($prop === $property) {
                $startClearing = true;
            }
        }
    }

    public function useSameAsPresentAddress($reset = true)
    {
        if ($this->samePresentAddressChckBox['checked']) {
            $this->permanentAddressFields['provinces'] = Province::where('region_code', $this->address['presentRegion'])
                ->pluck('name', 'province_code')
                ->toArray();

            $this->permanentAddressFields['cities'] = City::where('province_code', $this->address['presentProvince'])
                ->pluck('name', 'city_code')
                ->toArray();

            $this->permanentAddressFields['barangays'] = Barangay::where('city_code', $this->address['presentCity'])
                ->pluck('name', 'id')
                ->toArray();

            $this->address['permanentRegion'] = $this->address['presentRegion'] ?? $this->address['permanentRegion'];
            $this->address['permanentProvince'] = $this->address['presentProvince'] ?? $this->address['permanentProvince'];
            $this->address['permanentCity'] = $this->address['presentCity'] ?? $this->address['permanentCity'];
            $this->address['permanentBarangay'] = $this->address['presentBarangay'] ?? $this->address['permanentBarangay'];
            $this->address['permanentAddress'] = $this->address['presentAddress'] ?? $this->address['permanentAddress'];

            $this->dispatch('same-as-present-address');
        } else {
            if ($reset) {
                $this->resetPermanentAddressFields();
            }
        }
    }

    protected function resetPermanentAddressFields()
    {
        foreach ($this->permanentAddressProperties() as $property) {
            $this->address[explode('.', $property)[1]] = null;
        }
    }

    protected function permanentAddressProperties($hasAddress = true)
    {
        return [
            'address.permanentRegion',
            'address.permanentProvince',
            'address.permanentCity',
            'address.permanentBarangay',
            'address.permanentAddress'
        ];
    }

    protected function presentAddressProperties($hasAddress = true)
    {
        $array = [
            'address.presentRegion',
            'address.presentProvince',
            'address.presentCity',
            'address.presentBarangay',
        ];

        if ($hasAddress) {
            $array[] =  'address.presentAddress';
        }

        return $array;
    }

    /**
     * Accessor for regions, returning key / value pairs name and region_code.
     *
     * @return array
     */
    #[Computed]
    public function regions()
    {
        return Region::all()->pluck('name', 'region_code')->toArray();
    }

    /**
     * Accessor for provinces, returning key / value pairs name and province_code.
     *
     * @return array
     */
    #[Computed]
    public function provinces()
    {
        return Province::where('region_code', $this->initialAddressState['region'])
            ->pluck('name', 'province_code')
            ->toArray();
    }
}

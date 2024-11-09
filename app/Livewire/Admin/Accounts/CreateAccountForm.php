<?php

namespace App\Livewire\Admin\Accounts;

use App\Enums\Sex;
use App\Models\City;
use App\Models\Shift;
use App\Models\Region;
use App\Enums\UserRole;
use Livewire\Component;
use App\Models\Barangay;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\Province;
use App\Models\JobFamily;
use App\Enums\CivilStatus;
use App\Models\SpecificArea;
use App\Enums\UserPermission;
use App\Models\EmploymentStatus;
use Livewire\Attributes\Computed;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\CreateAccountForm as CreateAccountFormObject;

class CreateAccountForm extends Component
{
    /**
     * Create new instance of livewire form object.
     *
     * @var \App\Livewire\Forms\CreateAccountForm $form
     */
    public CreateAccountFormObject $form;
    
    /**
     * Populate addresses for present regions, provinces, cities, and barangays.
     * 
     * @var array<string, int>
     */
    public $presentFields = [
        'regions' => null, 
        'provinces' => null,
        'cities' => null,
        'barangays' => null,
    ];

    /**
     * Populate addresses for permanent regions, provinces, cities, and barangays.
     * 
     * @var array<string, int, null>
     */
    public $permanentFields = [
        'regions' => null, 
        'provinces' => null,
        'cities' => null,
        'barangays' => null,
    ];

    /**
     * Set the initial state/code of region and province.
     * 
     * I would assume that most of the applicants are based within the 
     * Region IV-A Calabarzon (04), specifically in Laguna (04034).
     * 
     * @var array<string>
     */
    private $initialState = [
        'region' => '04',
        'province' => '04034'
    ];

    /**
     * Use computed props for populating regions, provinces, and cities dropdowns.
     * 
     * @return void
     */
    public function mount()
    {
        $this->presentFields['regions'] = $this->regions;
        $this->permanentFields['regions'] = $this->regions;
        $this->presentFields['provinces'] = $this->provinces;
        $this->permanentFields['provinces'] =$this->provinces;
        $this->presentFields['cities'] =$this->cities;
        $this->permanentFields['cities'] = $this->cities;

        $this->form->presentRegion = $this->initialState['region'];
        $this->form->permanentRegion = $this->initialState['region'];
        $this->form->presentProvince = $this->initialState['province'];
        $this->form->permanentProvince = $this->initialState['province'];
    }

    /**
     * Handle authorization, validation, creation, and dispatching of account creation event.
     * 
     * @return void
     */
    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_EMPLOYEE_ACCOUNT)) {
            $this->form->reset();

            abort(403);
        }
        $this->form->validate();
        $this->form->create();
        $this->form->reset();

        $this->dispatch('account-created');
    }

    /**
     * Handle automatic dropdown options for provinces, cities, and barangays.
     * 
     * @param mixed $property
     * @return void
     */
    public function updated($property)
    {
        if ($property === 'form.presentRegion') {
            $this->presentFields['provinces'] = Province::where('region_code', $this->form->presentRegion)
                                                        ->pluck('name', 'province_code')
                                                        ->toArray();
        
            if ($this->form->presentRegion === '13') {
                $this->presentFields['cities'] = City::where('region_code', $this->form->presentRegion)
                                                    ->pluck('name', 'city_code')
                                                    ->toArray();
            }
        }
        if ($property === 'form.permanentRegion') {
            $this->permanentFields['provinces'] = Province::where('region_code', $this->form->permanentRegion)
                                                        ->pluck('name', 'province_code')
                                                        ->toArray();

            if ($this->form->permanentRegion === '13') {
                $this->permanentFields['cities'] = City::where('region_code', $this->form->permanentRegion)
                                                        ->pluck('name', 'city_code')
                                                        ->toArray();
            }
        }
        if ($property === 'form.presentProvince') {
            $this->presentFields['cities'] = City::where('province_code', $this->form->presentProvince)
                                                ->pluck('name', 'city_code')
                                                ->toArray();
        }
        if ($property === 'form.permanentProvince') {
            $this->permanentFields['cities'] = City::where('province_code', $this->form->permanentProvince)
                                                    ->pluck('name', 'city_code')
                                                    ->toArray();
        }
        if ($property === 'form.presentCity') {
            $this->presentFields['barangays'] = Barangay::where('city_code', $this->form->presentCity)
                                                        ->pluck('name', 'id')
                                                        ->toArray();
        }
        if ($property === 'form.permanentCity') {
            $this->permanentFields['barangays'] = Barangay::where('city_code', $this->form->permanentCity)
                                                        ->pluck('name', 'id')
                                                        ->toArray();
        }
    }
    
    /**
     * Accessor for regions, returning key / value pairs name and region_code.
     * 
     * @return array
     */
    #[Computed]
    public function regions()
    {
        return Region::all()->pluck('name','region_code')->toArray();
    }

    /**
     * Accessor for provinces, returning key / value pairs name and province_code.
     * 
     * @return array
     */
    #[Computed]
    public function provinces()
    {
        return Province::all()->pluck('name', 'province_code')->toArray();
    }

    /**
     * Accessor for provinces, returning key / value pairs name and city_code.
     * 
     * @return array
     */
    #[Computed]
    public function cities()
    {
        return City::where('province_code', $this->initialState['province'])
                ->pluck('name', 'city_code')
                ->toArray();
    }

    /**
     * Accessor for shifts, returning key / value pairs shift_name and shift_id.
     * 
     * @return array
     */
    #[Computed]
    public function shifts()
    {
        return Shift::all()->pluck('shift_name', 'shift_id')->toArray();
    }

    /**
     * Accessor for job families, returning key / value pairs job_family_name and job_family_id.
     * 
     * @return array
     */
    #[Computed]
    public function jobFamilies()
    {
        return JobFamily::all()->pluck('job_family_name', 'job_family_id')->toArray();
    }

    /**
     * Accessor for job titles, returning key / value pairs job_title and job_title_id.
     * 
     * @return array
     */
    #[Computed]
    public function jobTitles()
    {
        return JobTitle::all()->pluck('job_title', 'job_title_id')->toArray();
    }

    /**
     * Accessor for job levels, returning key / value pairs job_level_name and job_level_id.
     * 
     * @return array
     */
    #[Computed]
    public function jobLevels()
    {
        return JobLevel::all()->pluck('job_level_name', 'job_level_id')->toArray();
    }

    /**
     * Accessor for areas / branches, returning key / value pairs area_name and area_id.
     * 
     * @return array
     */
    #[Computed]
    public function areas()
    {
        return SpecificArea::all()->pluck('area_name', 'area_id')->toArray();
    }

    /**
     * Accessor for role names, returning a collection of keys and labels.
     * 
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    #[Computed]
    public function formattedRoles()
    {
        return Role::all()->mapWithKeys(function ($role) {
            $userRole = UserRole::tryFrom($role->name);
            return [
                $role->name => $userRole ? $userRole->label() : $role->name,
            ];
        });
    }

    /**
     * Accessor for employment statuses, returning key / value pairs emp_status_name and emp_status_id.
     * 
     * @return array
     */
    #[Computed]
    public function employmentStatuses()
    {
        return EmploymentStatus::all()->pluck('emp_status_name', 'emp_status_id')->toArray();
    }

    /**
     * Accessor for civil statuses, returning key / value pairs of enum cases and labels.
     * 
     * @return array
     */
    #[Computed]
    public function civilStatuses()
    {
        return CivilStatus::options();
    }

    /**
     * Accessor for sexes, returning key / value pairs of enum cases and labels.
     * 
     * @return array
     */
    #[Computed]
    public function sexes()
    {
        return Sex::options();
    }

    public function render()
    {
        return view('livewire.admin.accounts.create-account-form');
    }
}
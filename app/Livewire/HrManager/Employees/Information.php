<?php

namespace App\Livewire\HrManager\Employees;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Lazy;
use App\Http\Helpers\Timezone;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Cache;

#[Lazy(isolate: false)]
class Information extends Component
{
    #[Locked]
    public object $employee;

    private string $timezone;

    public function mount(Employee $employee)
    {
        $key = sprintf(config('cache.keys.profile.information'), $employee->employee_id);
        
        if (Cache::has($key)) {
            $this->employee = Cache::get($key);
        } else {
            $employee->loadMissing([
                'account',
                'jobTitle' => [
                    'jobLevel',
                    'jobFamily'
                ],
                'status',
                'shift',
                'application',
            ]);

            $this->employee = Cache::rememberForever($key, function () use ($employee) {
                return (object) [
                    'id'                    => $employee->employee_id,
                    'name'                  => $employee->full_name,
                    'email'                 => $employee->account->email,
                    'photo'                 => $employee->account->photo,
                    'fullPresentAddress'    => $employee->full_present_address,
                    'fullPermanentAddress'  => $employee->full_permanent_address,
                    'contactNo'             => $employee->contact_number,
                    'sex'                   => $employee->sex,
                    'civilStatus'           => $employee->civil_status,
                    'jobTitle'              => $employee->jobTitle->job_title,
                    'jobLevel'              => $employee->jobTitle->jobLevel->job_level,
                    'jobLevelName'          => $employee->jobTitle->jobLevel->job_level_name,
                    'jobFamily'             => $employee->jobTitle->jobFamily->job_family_name,
                    'employmentStatus'      => $employee->status->emp_status_name,
                    'shift'                 => $employee->shift->category->shift_name,
                    'shiftSched'            => $employee->shift->schedule,
                    'hiredAt'               => $employee?->application?->hired_at,
                    'dob'                   => $employee->date_of_birth,
                    'sss'                   => $employee->sss_no,
                    'philHealth'            => $employee->philhealth_no,
                    'tin'                   => $employee->tin_no,
                    'pagIbig'               => $employee->pag_ibig_no,
                ];
            });
        }
    }

    public function boot()
    {
        $this->timezone = Timezone::get();
    }

    public function placeholder()
    {
        return view('livewire.placeholder.profile');
    }

    public function render()
    {
        return view('livewire.hr-manager.employees.information');
    }
}

<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Information extends Component
{
    #[Locked]
    public $employee;

    public function mount()
    {
        $user = Auth::user();

        $key = sprintf(config('cache.keys.profile.information'), $user->account->employee_id);

        if (Cache::has($key)) {
            $this->employee = Cache::get($key);
        } else {
            $user->loadMissing([
                'account' => [
                    'jobTitle' => [
                        'jobLevel',
                        'jobFamily'
                    ],
                    'status',
                    'shift',
                    'application',
                ],
            ]);

            $this->employee = Cache::rememberForever($key, function () use ($user) {
                return (object) [
                    'id'                    => $user->account->employee_id,
                    'userId'                => $user->user_id,
                    'name'                  => $user->account->full_name,
                    'email'                 => $user->email,
                    'photo'                 => $user->photo,
                    'fullPresentAddress'    => $user->account->full_present_address,
                    'fullPermanentAddress'  => $user->account->full_permanent_address,
                    'contactNo'             => $user->account->contact_number,
                    'sex'                   => $user->account->sex,
                    'civilStatus'           => $user->account->civil_status,
                    'jobTitle'              => $user->account->jobTitle->job_title,
                    'jobLevel'              => $user->account->jobTitle->jobLevel->job_level,
                    'jobLevelName'          => $user->account->jobTitle->jobLevel->job_level_name,
                    'jobFamily'             => $user->account->jobTitle->jobFamily->job_family_name,
                    'employmentStatus'      => $user->account->status->emp_status_name,
                    'shift'                 => $user->account->shift->category->shift_name,
                    'shiftSched'            => $user->account->shift->schedule,
                    'hiredAt'               => $user->account?->jobDetail?->hired_at,
                    'dob'                   => $user->account->date_of_birth,
                    'sss'                   => $user->account->sss_no,
                    'philHealth'            => $user->account->philhealth_no,
                    'tin'                   => $user->account->tin_no,
                    'pagIbig'               => $user->account->pag_ibig_no,
                ];                   
            });
        }
    }

    public function render()
    {
        return view('livewire.profile.information');
    }
}

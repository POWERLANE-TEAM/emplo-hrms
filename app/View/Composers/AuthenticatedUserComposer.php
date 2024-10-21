<?php

namespace App\View\Composers;

use App\Http\Helpers\ChooseGuard;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AuthenticatedUserComposer
{
    // https://laravel.com/docs/11.x/views#view-composers

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $guard = Auth::guard(ChooseGuard::getByRequest());

        if ($guard->check()) {
            $user = Cache::flexible('user_' . $guard->id(), [30, 60], function () use ($guard) {
                return User::where('user_id', $guard->id())
                    ->with('roles')
                    ->first();
            });

            $role_name = $user->roles->pluck('name')->first();
            $view->with([
                'role_name' => $role_name,
                'user' => $user,
            ]);
        }
    }
}

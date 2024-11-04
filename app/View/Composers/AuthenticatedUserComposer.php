<?php

namespace App\View\Composers;

use App\Http\Helpers\RoutePrefix;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AuthenticatedUserComposer
{
    // https://laravel.com/docs/11.x/views#view-composers

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {

        $routePrefix = RoutePrefix::getByRequest();
        $guard = Auth::guard(Auth::getDefaultDriver());


        if ($guard->check()) {
            $user = $guard->user();

            $userPhoto = $user->photo ? Storage::url($user->photo) : null;
            $defaultAvatar = Storage::url('icons/default-avatar.png');

            $view->with([
                'user' => $user,
                'routePrefix' => $routePrefix,
                'userPhoto' => $userPhoto,
                'defaultAvatar' => $defaultAvatar,
            ]);
        }
    }
}

<?php

namespace App\View\Composers;

use App\Actions\GenerateRandomUserAvatar;
use Illuminate\Support\Facades\Auth;
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

        if (Auth::check()) {
            $user = Auth::user();

            $userPhoto = $user->photo ? Storage::url($user->photo) : null;
            $defaultAvatar = app(GenerateRandomUserAvatar::class)($user->account->full_name);

            $view->with([
                'user' => $user,
                'userPhoto' => $userPhoto,
                'defaultAvatar' => $defaultAvatar,
            ]);
        }
    }
}

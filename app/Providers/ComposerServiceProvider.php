<?php

namespace App\Providers;

use App\Http\Helpers\ChooseGuard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    private $full_page_views =
    [
        'components.layout.employee.nav.main-menu',
        'employee.hr-manager.index'
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        View::composer($this->full_page_views, function ($view) {

            if (Auth::guard(ChooseGuard::getByRequest())->check()) {
                $authenticated_user = Auth::guard(ChooseGuard::getByRequest())->user();
                $user = User::where('user_id', $authenticated_user->user_id)
                    ->with('roles')
                    ->first();

                $role_name = $user->roles->pluck('name')->first();

                $view->with([
                    'role_name' => $role_name,
                    'user' => $user,
                ]);
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Http\Helpers\ChooseGuard;
use App\View\Composers\AuthenticatedUserComposer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{


    /**
     * Views that require user data.
     *
     * @var array
     */
    private $views_needs_user_data = [
        'components.layout.employee.layout',
        'components.layout.applicant.layout',
        'components.layout.app',
        'employee.*.index',
    ];


    /**
     * Register services.
     */
    public function register(): void
    {
        // View::composer($this->views_needs_user_data,  AuthenticatedUserComposer::class);
    }

    /**
     * This method bootstraps view composers for views the same data.
     *
     * It is boostrapped in the `boot` method of the `AppServiceProvider`.
     *
     * See file://./AppServiceProvider.php
     *
     * It passes the following data:
     * - `user`: The authenticated user's data, including their roles.
     * - `role_name`: The name of the authenticated user's role.
     * - `nonce`: A Content Security Policy nonce for security.
     *
     * Update the list as needed.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer($this->views_needs_user_data, AuthenticatedUserComposer::class);
        View::composer('*', function ($view) {
            $guard = Auth::guard(ChooseGuard::getByRequest());

            $view->with(['nonce' => csp_nonce(), 'guard' => $guard]);
        });
    }
}

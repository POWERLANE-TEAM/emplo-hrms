<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\AccountType;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Events\UserLoggedout;
use App\Http\Helpers\ChooseGuard;
use App\Livewire\Auth\UnverifiedEmail;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (request()->is('employee/*')) {
            config(
                [
                    'fortify.guard' => 'employee',
                    'fortify.home' => '/employee/dashboard',
                ]
            );
        }
        if (request()->is('admin/*')) {
            config(
                [
                    'fortify.guard' => 'admin',
                    'fortify.home' => '/admin/dashboard',
                ]
            );
        }

        $this->app->when([AuthenticatedSessionController::class, AttemptToAuthenticate::class])
            ->needs(StatefulGuard::class)
            ->give(function () {
                return Auth::guard(ChooseGuard::getByReferrer());
            });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                try {
                    broadcast(new UserLoggedout($request->auth_broadcast_id))->toOthers();
                } catch (\Throwable $th) {
                    // avoid Pusher error: cURL error 7: Failed to connect to localhost port 8080 after 2209 ms: Couldn't connect to server
                    /* when websocket server is not started */
                }

                return redirect('/');
            }
        });

        $this->app->instance(LoginResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                $authUser = Auth::guard(ChooseGuard::getByReferrer())->user();

                // Redirection to previously visited page before being prompt to login
                // For example you visit /employee/payslip and you are not logged in
                // Instead of redirecting to dashboard after successful login you will be redirected to /employee/payslip
                $intended_url = Session::get('url.intended');

                if ($intended_url && $authUser) {
                    $route = Route::getRoutes()->match(Request::create($intended_url));
                    $middleware = $route->gatherMiddleware();

                    $has_access = true;

                    foreach ($middleware as $middleware_item) {

                        if (str_contains($middleware_item, 'permission:')) {
                            $permission = explode(':', $middleware_item)[1];
                            if (! $authUser->can($permission)) {
                                $has_access = false;
                                break;
                            }
                        }
                    }

                    if ($has_access) {
                        return redirect()->intended();
                    }
                }

                if ($authUser->account_type == AccountType::EMPLOYEE->value) {

                    if ($authUser->hasPermissionTo(UserPermission::VIEW_ADMIN_DASHBOARD->value)) {
                        return redirect('/admin/dashboard');
                    }

                    if ($authUser->hasAnyPermission([UserPermission::VIEW_EMPLOYEE_DASHBOARD->value, UserPermission::VIEW_HR_MANAGER_DASHBOARD->value])) {
                        return redirect('/employee/dashboard');
                    }
                }

                if ($authUser->account_type == AccountType::APPLICANT->value) {
                    return redirect('/applicant');
                }

                return redirect('/');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::verifyEmailView(fn() => app(UnverifiedEmail::class)->render());

        Fortify::loginView(function () {
            $view = match (true) {
                request()->is('employee/*') => 'livewire.auth.employees.login-view',
                request()->is('admin/*') => 'livewire.auth.admins.login-view',
                default => 'livewire.auth.applicants.login-view',
            };

            return view($view);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}

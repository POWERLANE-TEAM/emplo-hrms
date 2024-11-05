<?php

namespace App\Providers;

use App\Enums\GuardType;
use App\Enums\AccountType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Enums\UserPermission;
use App\Events\UserLoggedout;
use App\Http\Helpers\ChooseGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;
use App\Livewire\Auth\UnverifiedEmail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use App\Actions\Fortify\UpdateUserProfileInformation;
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

        config(['fortify.prefix' => ChooseGuard::getByReferrer()]);

        $this->app->when([AuthenticatedSessionController::class, AttemptToAuthenticate::class])
            ->needs(StatefulGuard::class)
            ->give(function () {
                return Auth::guard(ChooseGuard::getByReferrer());
            });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                $redirectUrl = match (Auth::getDefaultDriver()) {
                    GuardType::DEFAULT->value => '/login',
                    GuardType::EMPLOYEE->value => '/employee/login',
                    GuardType::ADMIN->value => '/admin/login',
                };

                try {
                    broadcast(new UserLoggedout($request->authBroadcastId, $redirectUrl))->toOthers();
                    return redirect($redirectUrl);
                } catch (\Throwable $th) {
                    // avoid Pusher error: cURL error 7: Failed to connect to localhost port 8080 after 2209 ms: Couldn't connect to server
                    /* when websocket server is not started */

                    \Log::error('Broadcast error: '.$th);
                    return redirect($redirectUrl);
                }
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
                $intendedUrl = Session::get('url.intended');

                if ($intendedUrl && $authUser) {
                    $route = Route::getRoutes()->match(Request::create($intendedUrl));
                    $middleware = $route->gatherMiddleware();

                    $hasAccess = true;

                    foreach ($middleware as $middlewareItem) {

                        if (str_contains($middlewareItem, 'permission:')) {
                            $permission = explode(':', $middlewareItem)[1];
                            if (! $authUser->can($permission)) {
                                $hasAccess = false;
                                break;
                            }
                        }
                    }

                    if ($hasAccess) {
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

            $guard = null;

            // Loop through all guards and check which one has authenticated user then use that guard
            $guards = GuardType::values();
            for ($i = 0; $i < count($guards); $i++) {
                $guardType = $guards[$i];
                $isAuthenticated = Auth::guard($guardType)->check();
                if ($isAuthenticated) {
                    $guard = $guardType;
                    $view = "$guard.dashboard";
                    /*TODO If web add check if guest or applicant? */
                    return redirect()->route($view);
                }
            }

            $view = match (true) {
                request()->is('employee/*') => 'livewire.auth.employees.login-view',
                request()->is('admin/*') => 'livewire.auth.admins.login-view',
                default => 'livewire.auth.applicants.login-view',
            };

            return view($view);
        });

        Fortify::twoFactorChallengeView(function() {
            return view('livewire.auth.two-factor-challenge-form-view');
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

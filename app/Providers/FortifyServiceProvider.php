<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Enums\AccountType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Enums\UserPermission;
use App\Events\UserLoggedout;
use App\Http\Helpers\RoutePrefix;
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
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\RoutePrefix as EnumsRoutePrefix;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        config(['fortify.prefix' => RoutePrefix::getByReferrer()]);

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                try {
                    broadcast(new UserLoggedout($request->authBroadcastId))->toOthers();
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
                $authUser = Auth::user();

                // if (! Auth::user()->hasRole(UserRole::ADVANCED)) {

                //     Auth::logout();

                //     session(['forbidden' => __('You\'re trying to access a forbidden resource.')]);
                // }

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

                        if (str_contains($middlewareItem, 'role:')) {
                            $role = explode(':', $middlewareItem)[1];
                            if (! $authUser->hasRole($role)) {
                                $has_access = false;
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

        Fortify::authenticateUsing(function (Request $request) {

            // Log::info('Request', ['request' => $request]);

            $routePrefix = RoutePrefix::getByRequest($request);

            Log::info("Request  $routePrefix");

            $user = User::where('email', $request->email)->first();

            if ($this->isUnauthorized($user, $routePrefix)) {
                $redirectPrefix = $this->getRedirectPrefix($user, $routePrefix);
                $routePrefixWithDot = !empty($redirectPrefix) ? "{$redirectPrefix}." : $redirectPrefix;
                $routeName = "{$routePrefixWithDot}login";
                $redirectUrl = url(route($routeName));

                session()->flash('redirectHint', $redirectUrl);
                abort(403, __('You\'re trying to access a forbidden resource.'));
            }

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                Log::info('Success ');

                return $user;
            }
        });

        Fortify::loginView(function () {

            return view('livewire.auth.login-view');
        });

        Fortify::twoFactorChallengeView(function () {
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

    private function isUnauthorized($user, $routePrefix): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->hasRole(UserRole::ADVANCED) && $routePrefix != EnumsRoutePrefix::ADVANCED->value) {
            return true;
        }

        if ($user->hasRole(UserRole::INTERMEDIATE) && $routePrefix != EnumsRoutePrefix::EMPLOYEE->value) {
            return true;
        }

        if ($user->getRoleNames()->isEmpty() && in_array($routePrefix, EnumsRoutePrefix::valuesNotDefault())) {
            return true;
        }

        return false;
    }

    private function getRedirectPrefix($user, $routePrefix): string
    {
        if ($user->hasRole(UserRole::ADVANCED) && $routePrefix != EnumsRoutePrefix::ADVANCED->value) {
            return EnumsRoutePrefix::ADVANCED->value;
        }

        if ($user->hasRole(UserRole::INTERMEDIATE) && $routePrefix != EnumsRoutePrefix::EMPLOYEE->value) {
            return EnumsRoutePrefix::EMPLOYEE->value;
        }

        return '';
    }
}

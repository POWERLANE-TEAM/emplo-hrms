<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\AccountType;
use App\Enums\ActivityLogName;
use App\Enums\RoutePrefix as EnumsRoutePrefix;
use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Events\UserLoggedout;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Helpers\RouteHelper;
use App\Livewire\Auth\UnverifiedEmail;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        config(['fortify.prefix' => RouteHelper::getByReferrer()]);

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                $redirectUrl = match (RouteHelper::getByReferrer()) {
                    'employee' => '/employee/login',
                    'admin' => '/admin/login',
                    default => '/login',
                };

                try {
                    broadcast(new UserLoggedout($request->authBroadcastId, $redirectUrl))->toOthers();
                } catch (\Throwable $th) {
                    // avoid Pusher error: cURL error 7: Failed to connect to localhost port 8080 after 2209 ms: Couldn't connect to server
                    /* when websocket server is not started */

                    Log::error('Broadcast error: '.$th);
                } finally {
                    return redirect($redirectUrl)->with('clearSessionStorageKeys', 'pageThemePreference');
                }
            }
        });

        $this->app->instance(LoginResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                $authUser = Auth::user();

                // sample only, not much
                activity()
                    ->by($authUser)
                    ->useLog(ActivityLogName::AUTHENTICATION->value)
                    ->log(Str::ucfirst($authUser->account->first_name).' logged in.');

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

                        // check first if redirect would be successful free from error status code
                        $testRequest = Request::create($intendedUrl, 'GET');
                        $testResponse = app()->handle($testRequest);
                        if ($testResponse->isOk()) {
                            return redirect($intendedUrl)->with('clearSessionStorageKeys', 'pageThemePreference');
                        }
                    }
                }

                if ($authUser->account_type == AccountType::EMPLOYEE->value) {

                    if ($authUser->hasPermissionTo(UserPermission::VIEW_ADMIN_DASHBOARD->value)) {
                        return redirect('/admin/dashboard')->with('clearSessionStorageKeys', 'pageThemePreference');
                    }

                    if ($authUser->hasAnyPermission([UserPermission::VIEW_EMPLOYEE_DASHBOARD->value, UserPermission::VIEW_HR_MANAGER_DASHBOARD->value])) {
                        return redirect('/employee/dashboard')->with('clearSessionStorageKeys', 'pageThemePreference');
                    }
                }

                if ($authUser->account_type == AccountType::APPLICANT->value) {
                    return redirect('/application')->with('clearSessionStorageKeys', 'pageThemePreference');
                }

                return redirect('/')->with('clearSessionStorageKeys', 'pageThemePreference');
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

        Fortify::verifyEmailView(fn () => app(UnverifiedEmail::class)->render());

        Fortify::authenticateUsing(function (Request $request) {

            $routePrefix = RouteHelper::getByRequest($request);

            $user = User::where('email', $request->email)->first();

            if ($this->isUnauthorized($user, $routePrefix)) {
                $redirectPrefix = $this->getRedirectPrefix($user, $routePrefix);
                $routePrefixWithDot = ! empty($redirectPrefix) ? "{$redirectPrefix}." : $redirectPrefix;
                $routeName = "{$routePrefixWithDot}login";
                $redirectUrl = url(route($routeName));

                session()->flash('redirectHint', $redirectUrl);
                abort(403, __('You\'re trying to access a forbidden resource.'));
            }

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
        });

        Fortify::loginView(function () {
            return view('livewire.auth.login-view');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('livewire.auth.two-factor-challenge-form-view');
        });

        // Form to enter the email address to send the password reset link
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return app(ResetPasswordController::class)->index($request);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    private function isUnauthorized($user, $routePrefix): bool
    {
        if (! $user) {
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

        if (($user->hasRole(UserRole::INTERMEDIATE) || $user->hasRole(UserRole::BASIC)) && $routePrefix != EnumsRoutePrefix::EMPLOYEE->value) {
            return EnumsRoutePrefix::EMPLOYEE->value;
        }

        return '';
    }
}

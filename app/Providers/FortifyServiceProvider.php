<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\AccountType;
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
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Actions\AttemptToAuthenticate;

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
                    'fortify.home'  => '/employee/dashboard'
                ]
            );
        }
        if (request()->is('admin/*')) {
            config(
                [
                    'fortify.guard' => 'admin',
                    'fortify.home'  => '/admin/dashboard'
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
                // Redirect to previously visited page before being prompt to login
                if (session()->has('url.intended')) {
                    // Add Check if has permission to access then
                    if (true) {
                        return redirect()->intended();
                    }
                }

                $authenticated_user = Auth::guard(ChooseGuard::getByReferrer())->user();

                $user_with_role_and_account = User::where('user_id', $authenticated_user->user_id)
                    ->with(['roles'])
                    ->first();

                if ($authenticated_user->account_type == AccountType::EMPLOYEE->value) {

                    if ($user_with_role_and_account->hasRole(UserRole::ADVANCED->value)) {
                        return redirect('/admin/dashboard');
                    }

                    if ($user_with_role_and_account->hasRole([UserRole::BASIC->value, UserRole::INTERMEDIATE->value])) {
                        return redirect('/employee/dashboard');
                    }
                }

                if ($authenticated_user->account_type == AccountType::APPLICANT->value) {
                    //
                }
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

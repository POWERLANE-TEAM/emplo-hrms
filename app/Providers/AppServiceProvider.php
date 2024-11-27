<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Pulse\Facades\Pulse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Includes similar data without repeating code.
         */
        $this->app->register(ComposerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)->max(72);

            return
                $rule->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                    ->rules(['not_regex:/\s/']); // No spaces allowed
        });

        Validator::extend('valid_email_dns', function ($attributes, $value, $parameters, $validator) {
            $data = file_get_contents(Vite::asset('resources/js/email-domain-list.json'));
            $email_domains = json_decode($data, true);

            // Extract the domain from the email
            $email_domain = substr(strrchr($value, '@'), 1);

            return in_array($email_domain, $email_domains['valid_email']);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Powerlane Account')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });

        /**
         * Store strings representing the models.
         *
         * Instead of inserting import names of App\Models\ModelName in the column_type, we
         * can store strings representing the model (e.g.: 'guest' => 'App\Models\Guest').
         *
         * @see https://laravel.com/docs/11.x/eloquent-relationships#custom-polymorphic-types
         */
        Relation::enforceMorphMap([
            'guest' => 'App\Models\Guest',
            'user' => 'App\Models\User',
            'outsourced_trainer' => 'App\Models\OutsourcedTrainer',
            'employee' => 'App\Models\Employee',
            'applicant' => 'App\Models\Applicant',
            'performance_evaluation' => 'App\Models\PerformanceEvaluation',
            'overtime' => 'App\Models\Overtime',
            'employee_leave' => 'App\Models\EmployeeLeave',
            'job_vacancy' => 'App\Models\JobVacancy',
            'preemp_requirement' => 'App\Models\PreempRequirement',
            'province' => 'App\Models\Province',
            'announcement' => 'App\Models\Announcement',
            'job_family' => 'App\Models\JobFamily',
            'job_title' => 'App\Models\JobTitle',
            'performance_category' => 'App\Models\PerformanceCategory',
            'performance_rating' => 'App\Models\PerformanceRating',
            'holiday' => 'App\Models\Holiday',
        ]);

        BroadcastServiceProvider::class;

        /**
         * For cards that display information about users:
         * - making requests
         * - experiencing slow endpoints
         * - dispatching jobs
         *
         * @see https://laravel.com/docs/11.x/pulse#dashboard-resolving-users
         */
        Pulse::user(fn ($user) => [
            'name' => $user->account->full_name,
            'extra' => $user->email,
            'avatar' => $user->photo ?? Storage::url('icons/default-avatar.png'),
        ]);

        /**
         * This will only allow user with advanced role to access the pulse dashboard.
         *
         * @see https://laravel.com/docs/11.x/pulse#dashboard-authorization
         */
        Gate::define('viewPulse', function (User $user) {
            return $user->hasRole(UserRole::ADVANCED);
        });

        Vite::useAggressivePrefetching();
    }
}

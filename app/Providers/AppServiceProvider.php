<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    protected $whitelist = [
        'red',
        'yellow',
        'green',
        'blue',
        'black',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
                    ->uncompromised();
        });

        Validator::extend('valid_email_dns', function ($attributes, $value, $parameters, $validator) {
            $data = file_get_contents(Vite::asset('resources/js/email-domain-list.json'));
            $email_domains = json_decode($data, true);

            // Extract the domain from the email
            $email_domain = substr(strrchr($value, '@'), 1);

            return in_array($email_domain, $email_domains['valid_email']);
        }, 'Email service provider is not allowed.');

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Powerlane Account')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });

        // stores strings representing the models in the tables
        // e.g: from column_type = App\Models\OutsourcedTrainer to column_type = outsourced_trainer
        // ref: https://laravel.com/docs/11.x/eloquent-relationships#custom-polymorphic-types
        Relation::enforceMorphMap([
            'outsourced_trainer' => 'App\Models\OutsourcedTrainer',
            'employee' => 'App\Models\Employee',
            'applicant' => 'App\Models\Applicant',
        ]);
    }
}

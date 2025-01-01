<?php

namespace App\Providers;

use App\Models\User;
use App\Enums\UserRole;
use App\Policies\EmployeeLeavePolicy;
use App\Policies\IncidentPolicy;
use App\Policies\IssuePolicy;
use App\Policies\OvertimePolicy;
use Laravel\Pulse\Facades\Pulse;
use App\Providers\Form\FormWizardServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use App\Http\Helpers\BiometricDevice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Broadcasting\BroadcastServiceProvider;

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

        $this->app->singleton(BiometricDevice::class, function () {
            return new BiometricDevice();
        });
        
        $this->app->register(FormWizardServiceProvider::class);
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
            'attendance_log' => 'App\Models\AttendanceLog',
            'incident_attachment' => 'App\Models\IncidentAttachment',
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
        Pulse::user(fn($user) => [
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

        /** Overtime Policies */
        Gate::define('submitOvertimeRequest', [OvertimePolicy::class, 'submitOvertimeRequest']);
        Gate::define('updateOvertimeRequest', [OvertimePolicy::class, 'updateOvertimeRequest']);
        Gate::define('submitOvertimeRequestToday', [OvertimePolicy::class, 'submitOvertimeRequestToday']);
        Gate::define('submitNewOrAnotherOvertimeRequest', [OvertimePolicy::class, 'submitNewOrAnotherOvertimeRequest']);
        Gate::define('approveOvertimeSummaryInitial', [OvertimePolicy::class, 'approveOvertimeSummaryInitial']);
        Gate::define('approveOvertimeSummarySecondary', [OvertimePolicy::class, 'approveOvertimeSummarySecondary']);
        Gate::define('approveOvertimeSummaryTertiary', [OvertimePolicy::class, 'approveOvertimeSummaryTertiary']);
        Gate::define('viewOvertimeRequestAsSecondaryApprover', [OvertimePolicy::class, 'viewOvertimeRequestAsSecondaryApprover']);
        Gate::define('viewSubordinateOvertimeRequest', [OvertimePolicy::class, 'viewSubordinateOvertimeRequest']);
        Gate::define('updateAllOvertimeRequest', [OvertimePolicy::class, 'updateAllOvertimeRequest']);
        Gate::define('editOvertimeRequest', [OvertimePolicy::class, 'editOvertimeRequest']);
        Gate::define('authorizeOvertimeRequest', [OvertimePolicy::class, 'authorizeOvertimeRequest']);
        Gate::define('viewOvertimeSummary', [OvertimePolicy::class, 'viewOvertimeSummary']);

        /** Employee Leave Policies */
        Gate::define('fileLeaveRequest', [EmployeeLeavePolicy::class, 'fileLeaveRequest']);
        Gate::define('viewLeaveRequest', [EmployeeLeavePolicy::class, 'viewLeaveRequest']);
        Gate::define('updateLeaveRequest', [EmployeeLeavePolicy::class, 'updateLeaveRequest']);
        Gate::define('viewSubordinateLeaveRequest', [EmployeeLeavePolicy::class, 'viewSubordinateLeaveRequest']);
        Gate::define('approveSubordinateLeaveRequest', [EmployeeLeavePolicy::class, 'approveSubordinateLeaveRequest']);
        Gate::define('approveAnyLeaveRequest', [EmployeeLeavePolicy::class, 'approveAnyLeaveRequest']);
        Gate::define('approveLeaveRequestFinal', [EmployeeLeavePolicy::class, 'approveLeaveRequestFinal']);

        /** Issue Policies */
        Gate::define('submitIssueReport', [IssuePolicy::class, 'submitIssueReport']);
        Gate::define('viewIssueReport', [IssuePolicy::class, 'viewIssueReport']);
        Gate::define('viewAnyIssueReport', [IssuePolicy::class, 'viewAnyIssueReport']);
        Gate::define('updateIssueStatus', [IssuePolicy::class, 'updateIssueStatus']);

        /** Incident Policies */
        Gate::define('createIncidentReport', [IncidentPolicy::class, 'createIncidentReport']);
        Gate::define('updateIncidentReport', [IncidentPolicy::class, 'updateIncidentReport']);

        Vite::useAggressivePrefetching();
    }
}

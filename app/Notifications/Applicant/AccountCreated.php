<?php

namespace App\Notifications\Applicant;

use App\Http\Helpers\Timezone;
use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Time;

class AccountCreated extends Notification
{
    use Queueable;

    private array $channels;

    /**
     * Create a new notification instance.
     */
    public function __construct(private string $userId, private string $applicationId, ?array $channels = ['mail'])
    {
        $allowedChannels = ['mail', 'database', 'broadcast'];
        $this->channels = array_intersect($channels, $allowedChannels);
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user = User::select('account_type', 'account_id', 'email')
            ->with(['account' => function ($query) {
                $query->select('applicant_id', 'first_name', 'middle_name', 'last_name', 'contact_number');
            }])
            ->findOrFail($this->userId);

        Log::info('AccountCreated@toMail', ['userId' => $this->userId, 'user' => $user]);

        $applicant = $user ? $user->account : null;

        Log::info('AccountCreated@toMail', ['applicant' => $applicant]);

        $application = Application::select('job_vacancy_id')->with(['vacancy' => function ($query) {
            $query->select('job_vacancy_id', 'job_title_id')->with(['jobTitle' => function ($query) {
                $query->select('job_title_id', 'job_title');
            }]);
        }])->find($this->applicationId);

        Log::info('AccountCreated@toMail', ['application' => $application]);

        $dateTime = Carbon::now()->toDateTimeString();
        [$timezone, $timezoneOffset] = Timezone::get()->withOffset();

        return (new MailMessage)
            ->greeting('Hello ' . $applicant->fullName . '!')
            ->line('We have received your application for the position of ' . $application->vacancy->jobTitle->job_title . $dateTime . ' (' . $timezone . ' GMT' . $timezoneOffset . ' timezone).')
            ->action('View Application', URL::route('applicant.dashboard', ['application' => $this->applicationId]))
            ->line('Thank you for applying!')
            ->line('You will be notified of the application status.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new-applicant',
            'label' => ['applicants', 'new'],
            'userId' => $this->userId,
            'model' => 'applications',
            'modelId' => $this->applicationId,
        ];
    }
}

<?php

namespace App\Notifications\Applicant;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResumeRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sender;

    protected Application $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $sender, Application $application)
    {
        $this->sender = $sender;
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $applicationDate = $this->application->applicant->created_at->format('F j, Y');
        $jobTitle = $this->application->vacancy->jobTitle->job_title;
        $userName = $notifiable->account->fullName;

        return (new MailMessage)
            ->from($this->sender['email'])
            ->subject('Application Rejected')
            ->greeting('Hello '.$userName.'!')
            ->line('We regret to inform you that your application for the position of '.$jobTitle.' submitted on '.$applicationDate.' has been rejected.')
            ->line('Thank you for your interest in our company.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

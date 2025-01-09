<?php

namespace App\Notifications\Applicant;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sender;

    protected $examStart;

    protected $examEnd;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $sender, string $examStart, string $examEnd)
    {
        $this->sender = $sender;
        $this->examStart = $examStart;
        $this->examEnd = $examEnd;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // add more like 'database' (notification) or 'broadcast' (popup)
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $timezone = env('APP_TIMEZONE', 'UTC');

        $examStart = Carbon::parse($this->examStart)->setTimezone($timezone);

        $examStartFormatted = Carbon::parse($this->examStart)->setTimezone($timezone)->format('F j, Y, g:i A');
        $examEndFormatted = Carbon::parse($this->examEnd)->setTimezone($timezone)->format('F j, Y, g:i A');
        $timezoneOffset = $examStart->format('P');

        $userName = $notifiable->account->fullName;

        return (new MailMessage)
            ->from($this->sender['email'])
            ->subject('Exam Scheduled Notification')
            ->greeting('Hello '.$userName.'!')
            ->line('Congratulations! You have been qualified for the next stage of our recruitment process.')
            ->line('An exam has been scheduled for you.')
            ->line('The exam will start at **'.$examStartFormatted.'** and end at **'.$examEndFormatted.'** in the **'.$timezone.' (GMT'.$timezoneOffset.')** timezone.')
            // ->action('View Exam Details', url('/exam-details'))
            ->line('We wish you the best of luck!')
            ->line('Thank you for your interest in joining our team!');
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

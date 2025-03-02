<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $notifiable->loadMissing('account');

        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $time = Carbon::now()->format('F j, Y h:i A');
        $timezone = config('app.timezone');

        return (new MailMessage)
            ->greeting('Hello '.$notifiable->account->fullname.',')
            ->line('Your password has been reset successfully on '.$time.' '.$timezone.'.')
            ->line('If you did not request this change, please contact our support team immediately.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toDatabase($notifiable): array
    {
        $time = Carbon::now()->format('F j, Y h:i A');
        $timezone = config('app.timezone');

        return [
            'password-reset' => $notifiable->account->fullname.', your password has been reset successfully on '.$time.' '.$timezone.'.',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(): string
    {
        return 'password-reset';
    }
}

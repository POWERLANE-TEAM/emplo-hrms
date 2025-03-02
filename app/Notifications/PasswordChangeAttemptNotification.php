<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class PasswordChangeAttemptNotification extends Notification implements ShouldQueue
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
            ->greeting('Hello ' . $notifiable->account->fullname . ',')
            ->line('There was an attempt to change your password on ' . $time . ' ' . $timezone . ' that was the same as your current password.')
            ->line('If you did not request this change, please contact our support team immediately.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        $time = Carbon::now()->format('F j, Y h:i A');
        $timezone = config('app.timezone');

        return [
            'password-change-attempt' => $notifiable->account->fullname . ', there was an attempt to change your password on ' . $time . ' ' . $timezone . ' that was the same as your current password.',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(): string
    {
        return 'password-change-attempt';
    }
}
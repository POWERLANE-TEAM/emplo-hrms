<?php

namespace App\Notifications;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private $url;

    private $formattedRole;

    /**
     * Create a new notification instance.
     */
    public function __construct(private User $user, private string $password)
    {
        $role = $user->getRoleNames()->first();

        $this->url = match ($role) {
            'basic', 'intermediate' => 'employee',
            'advanced' => 'admin',
        };

        $this->formattedRole = match ($role) {
            'basic' => UserRole::BASIC->label(),
            'intermediate' => UserRole::INTERMEDIATE->label(),
            'advanced' => UserRole::ADVANCED->label(),
        };

        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Created')
            ->line('We are writing to inform that Powerlane has created your online account.')
            ->line('Password: '.$this->password)
            ->line('You can use these email and password to login to your account')
            ->action('Login now', url($this->url.'/login'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'employee-account-created' => 'Account successfully created for '.$this->user->email.' of type '.$this->formattedRole.'.',
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(): string
    {
        return 'employee-account-created';
    }
}

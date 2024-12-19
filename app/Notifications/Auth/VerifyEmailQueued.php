<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    // https://stackoverflow.com/questions/52644934/how-to-queue-laravel-5-7-email-verification-email-sending/52647112#52647112:~:text=VerifyEmail%3B%0A%0Aclass-,VerifyEmailQueued,-extends%20VerifyEmail%20implements
}

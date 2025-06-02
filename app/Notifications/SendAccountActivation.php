<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class SendAccountActivation extends Notification
{
    use Queueable;

   public function __construct(public $user) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'auth.account.activate.form',
            Carbon::now()->addMinutes(60),
            ['user' => $this->user->id]
        );

        return (new MailMessage)
            ->subject('Activate Your Account')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('Click the button below to activate your account.')
            ->action('Activate Account', $url)
            ->line('This link will expire in 60 minutes.');
    }
}

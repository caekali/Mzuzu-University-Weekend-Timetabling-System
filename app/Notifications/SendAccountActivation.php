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
            'activation.form',
            Carbon::now()->addMinutes(60),
            ['userId' => $this->user->id]
        );

        return (new MailMessage)
            ->subject('Activate Your Account')
            ->greeting('Hello ' . $this->user->first_name . ' ' . $this->user->last_name . ',')
            ->line('Click the button below to activate your account.')
            ->action('Activate Account', $url)
            ->line('This link will expire in 60 minutes.');
    }
}

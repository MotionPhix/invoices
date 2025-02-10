<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyClientEmail extends Notification implements ShouldQueue
{
  use Queueable;

  public function via($notifiable)
  {
    return ['mail'];
  }

  public function toMail($notifiable)
  {
    $verificationUrl = URL::temporarySignedRoute(
      'client.verify-email',
      now()->addHours(48),
      [
        'id' => $notifiable->id,
        'token' => $notifiable->email_verification_token
      ]
    );

    return (new MailMessage)
      ->subject('Verify Your Email Address')
      ->line('Please click the button below to verify your email address.')
      ->action('Verify Email Address', $verificationUrl)
      ->line('If you did not create an account, no further action is required.');
  }
}

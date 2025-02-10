<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientPortalLoginLink extends Notification
{
  use Queueable;

  private $token;

  public function __construct($token)
  {
    $this->token = $token;
  }

  public function via($notifiable)
  {
    return ['mail'];
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Your Client Portal Login Link')
      ->line('Click the button below to log in to your client portal.')
      ->action('Log In', route('client-portal.login.token', $this->token))
      ->line('This login link will expire in 24 hours.')
      ->line('If you did not request this login link, no further action is required.');
  }
}

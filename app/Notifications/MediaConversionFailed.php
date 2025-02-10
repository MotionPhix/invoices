<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaConversionFailed extends Notification
{
  use Queueable;

  public function __construct(protected Media $media) {}

  public function via($notifiable): array
  {
    return ['mail', 'database'];
  }

  public function toMail($notifiable): MailMessage
  {
    return (new MailMessage)
      ->error()
      ->subject('Media Conversion Failed')
      ->line("The conversion of file '{$this->media->file_name}' has failed.")
      ->line('Our team has been notified and will look into this issue.');
  }

  public function toArray($notifiable): array
  {
    return [
      'media_id' => $this->media->id,
      'file_name' => $this->media->file_name,
      'collection_name' => $this->media->collection_name,
    ];
  }
}

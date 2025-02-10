<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMediaCollection
{
  use InteractsWithMedia;

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('documents')
      ->acceptsMimeTypes([
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png'
      ])
      ->useDisk('media') // You can configure this in filesystems.php
      ->withResponsiveImages(); // For images only
  }

  public function registerMediaConversions(Media $media = null): void
  {
    // Only perform conversions on images
    if ($media && !$media->mime_type) {
      return;
    }

    /*if (!$media || !in_array($media->mime_type, ['image/jpeg', 'image/png'])) {
      return;
    }*/

    // Small thumbnail
    $this->addMediaConversion('thumb')
      ->width(150)
      ->height(150)
      ->queued(); // Make it queued

    // Medium size
    $this->addMediaConversion('medium')
      ->width(400)
      ->height(400)
      ->queued(); // Make it queued

    // Large size
    $this->addMediaConversion('large')
      ->width(800)
      ->height(800)
      ->queued(); // Make it queued

    // Add image conversions only for image files
    $this->addMediaConversion('thumbnail')
      ->width(200)
      ->height(200)
      ->performOnCollections('documents')
      ->nonQueued(); // Process immediately, remove if you want to use queues
  }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Illuminate\Support\Facades\Storage;

class Media extends BaseMedia
{
  protected $appends = [
    'formatted_size',
    'download_url',
    'preview_url',
  ];

  public function getFormattedSizeAttribute(): string
  {
    $bytes = $this->size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024; $i++) {
      $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
  }

  public function getDownloadUrlAttribute(): string
  {
    return route('media.download', $this->id);
  }

  public function getPreviewUrlAttribute(): ?string
  {
    if ($this->hasGeneratedConversion('preview')) {
      return $this->getUrl('preview');
    }

    if (Str::startsWith($this->mime_type, 'image/')) {
      return $this->getUrl();
    }

    return null;
  }

  public function isImage(): bool
  {
    return Str::startsWith($this->mime_type, 'image/');
  }

  public function isPdf(): bool
  {
    return $this->mime_type === 'application/pdf';
  }

  public function isOfficeDocument(): bool
  {
    return in_array($this->mime_type, [
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
  }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MediaConversionFailed;

class HandleFailedMediaConversion implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(
    protected Media $media,
    protected string $error
  ) {}

  public function handle(): void
  {
    // Log the failure
    Log::error("Media conversion failed for media ID: {$this->media->id}", [
      'error' => $this->error,
      'file' => $this->media->file_name,
      'model' => $this->media->model_type,
      'model_id' => $this->media->model_id,
    ]);

    // You could notify administrators or the user who uploaded the file
    if ($this->media->model && method_exists($this->media->model, 'user')) {
      $user = $this->media->model->user;
      if ($user) {
        Notification::send($user, new MediaConversionFailed($this->media));
      }
    }
  }
}

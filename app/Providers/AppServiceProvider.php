<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      // Create media directory if it doesn't exist
      $mediaPath = Storage::disk('media')->path('');

      if (!file_exists($mediaPath)) {
        mkdir($mediaPath, 0777, true);
      }
    }
}

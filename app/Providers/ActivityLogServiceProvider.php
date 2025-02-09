<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class ActivityLogServiceProvider extends ServiceProvider
{
  public function boot()
  {
    Activity::saving(function (Activity $activity) {
      $activity->properties = $activity->properties->merge([
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
      ]);
    });
  }
}

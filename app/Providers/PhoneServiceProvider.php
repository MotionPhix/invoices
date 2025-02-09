<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
      try {
        $phone = new PhoneNumber($value, $parameters[0] ?? null);
        return $phone->isValid();
      } catch (\Exception $e) {
        return false;
      }
    });
  }
}

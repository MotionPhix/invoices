<?php

namespace App\Models;

use App\Notifications\VerifyClientEmail;
use App\Traits\BootUuid;
use App\Traits\HasMediaCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;

class Client extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, LogsActivity, BootUuid, HasMediaCollection;

  protected $fillable = [
    'name',
    'email',
    'phone',
    'company_name',
    'vat_number',
    'billing_address',
    'billing_city',
    'billing_state',
    'billing_postal_code',
    'billing_country',
    'use_billing_for_shipping',
    'shipping_address',
    'shipping_city',
    'shipping_state',
    'shipping_postal_code',
    'shipping_country',
    'user_id',
    'notes',
    'currency',
    'status',

    'email_verified_at',
    'email_verification_token',
    'email_verification_sent_at',
    'login_token',
    'login_token_expires_at'
  ];

  protected $casts = [
    'use_billing_for_shipping' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',

    'email_verified_at' => 'datetime',
    'email_verification_sent_at' => 'datetime',
    'login_token_expires_at' => 'datetime',
  ];

  protected $hidden = [
    'login_token',
  ];

  public function invoices()
  {
    return $this->hasMany(Invoice::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function payments()
  {
    return $this->hasMany(Payment::class);
  }

  public function supportRequests()
  {
    return $this->hasMany(SupportRequest::class);
  }

  public function generateLoginToken()
  {
    $this->login_token = Str::random(64);
    $this->login_token_expires_at = now()->addHours(24);
    $this->save();

    return $this->login_token;
  }

  public function hasValidLoginToken()
  {
    return $this->login_token && $this->login_token_expires_at && $this->login_token_expires_at->isFuture();
  }

  // Accessors & Mutators
  protected function getFullAddressAttribute()
  {
    return implode(', ', array_filter([
      $this->billing_address,
      $this->billing_city,
      $this->billing_state,
      $this->billing_postal_code,
      $this->billing_country,
    ]));
  }

  // Add these new methods for email verification
  public function hasVerifiedEmail()
  {
    return ! is_null($this->email_verified_at);
  }

  public function markEmailAsVerified()
  {
    return $this->forceFill([
      'email_verified_at' => $this->freshTimestamp(),
      'email_verification_token' => null,
    ])->save();
  }

  public function sendEmailVerificationNotification()
  {
    $this->forceFill([
      'email_verification_token' => Str::random(60),
      'email_verification_sent_at' => $this->freshTimestamp(),
    ])->save();

    $this->notify(new VerifyClientEmail);
  }

  protected function getFullShippingAddressAttribute()
  {
    if ($this->use_billing_for_shipping) {
      return $this->full_address;
    }

    return implode(', ', array_filter([
      $this->shipping_address,
      $this->shipping_city,
      $this->shipping_state,
      $this->shipping_postal_code,
      $this->shipping_country,
    ]));
  }

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly([
        'name',
        'email',
        'phone',
        'company_name',
        'billing_address',
        'shipping_address',
        'status',
      ])
      ->logOnlyDirty()
      ->dontSubmitEmptyLogs()
      ->setDescriptionForEvent(fn(string $eventName) => "Client was {$eventName}")
      ->useLogName('client');
  }

  public function activities()
  {
    return $this->morphMany(\Spatie\Activitylog\Models\Activity::class, 'subject');
  }
}

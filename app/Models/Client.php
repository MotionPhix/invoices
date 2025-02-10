<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
  use HasFactory, SoftDeletes, LogsActivity, BootUuid;

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
  ];

  protected $casts = [
    'use_billing_for_shipping' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  public function invoices()
  {
    return $this->hasMany(Invoice::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
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

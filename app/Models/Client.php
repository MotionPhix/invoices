<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'email',
    'phone',
    'company_name',
    'vat_number',
    'billing_address',
    'shipping_address',
    'country',
    'city',
    'postal_code',
    'notes',
    'currency',
    'status',
  ];

  public function invoices()
  {
    return $this->hasMany(Invoice::class);
  }

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];
}

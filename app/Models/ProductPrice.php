<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'client_id',
    'price',
    'minimum_quantity',
    'currency',
    'valid_from',
    'valid_until',
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'minimum_quantity' => 'integer',
    'valid_from' => 'datetime',
    'valid_until' => 'datetime',
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function client()
  {
    return $this->belongsTo(Client::class);
  }

  public function isValid(): bool
  {
    $now = now();

    if ($this->valid_until && $now->gt($this->valid_until)) {
      return false;
    }

    if ($this->valid_from && $now->lt($this->valid_from)) {
      return false;
    }

    return true;
  }
}

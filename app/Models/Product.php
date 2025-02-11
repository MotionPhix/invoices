<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasMediaCollection;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, HasMediaCollection;

  protected $fillable = [
    'name',
    'slug',
    'sku',
    'description',
    'price',
    'cost',
    'type',
    'is_active',
    'category_id',
    'unit',
    'track_inventory',
    'stock',
    'low_stock_threshold',
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'cost' => 'decimal:2',
    'is_active' => 'boolean',
    'track_inventory' => 'boolean',
    'stock' => 'integer',
    'low_stock_threshold' => 'integer',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($product) {
      if (! $product->slug) {
        $product->slug = Str::slug($product->name);
      }

      if (! $product->sku) {
        $product->sku = static::generateSku();
      }
    });
  }

  public static function generateSku()
  {
    do {
      $sku = strtoupper(Str::random(8));
    } while (static::where('sku', $sku)->exists());

    return $sku;
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function prices()
  {
    return $this->hasMany(ProductPrice::class);
  }

  public function getCurrentPriceForClient($clientId = null)
  {
    $query = $this->prices()
      ->where(function ($query) {
        $query->whereNull('valid_until')
          ->orWhere('valid_until', '>', now());
      })
      ->where('valid_from', '<=', now());

    if ($clientId) {
      $query->where('client_id', $clientId);
    } else {
      $query->whereNull('client_id');
    }

    return $query->orderBy('minimum_quantity')
      ->first();
  }

  public function isLowStock(): bool
  {
    if (!$this->track_inventory || is_null($this->low_stock_threshold)) {
      return false;
    }

    return $this->stock <= $this->low_stock_threshold;
  }
}

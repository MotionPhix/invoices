<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
  use HasFactory;

  const STATUS_DRAFT = 'draft';
  const STATUS_PAID = 'paid';
  const STATUS_PARTIAL = 'partial';
  const STATUS_CANCELED = 'canceled';

  protected $fillable = [
    'user_id',
    'invoice_number',
    'invoice_date',
    'description',
    'status',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function items()
  {
    return $this->hasMany(InvoiceItem::class);
  }

  public function getSubtotalAttribute()
  {
    return $this->items->sum(function ($item) {
      return $item->quantity * $item->unit_price;
    });
  }

  public function getVatAmountAttribute()
  {
    $settings = Settings::first();
    return $this->subtotal * ($settings->vat_rate / 100);
  }

  public function getTotalAmountAttribute()
  {
    return $this->subtotal + $this->vatAmount;
  }

  public static function generateInvoiceNumber()
  {
    $settings = Settings::first();

    $prefix = $settings->invoice_prefix;

    $suffix = $settings->invoice_suffix;

    $lastInvoice = self::orderBy('id', 'desc')->first();

    /*$number = $lastInvoice
      ? intval(substr($lastInvoice->invoice_number, strlen($prefix))) + 1
      : $settings->invoice_start_number;

    return $prefix . str_pad($number, $settings->invoice_number_length, '0', STR_PAD_LEFT);*/

    if ($lastInvoice) {

      $lastNumber = intval(str_replace([$prefix, $suffix], '', $lastInvoice->invoice_number));
      $newNumber = $lastNumber + 1;

    } else {

      $newNumber = $settings->invoice_start_number;

    }

    return $prefix . str_pad($newNumber, $settings->invoice_number_length, '0', STR_PAD_LEFT) . $suffix;
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($invoice) {

      if (! isset($invoice->user_id)) {

        $invoice->user_id = auth()->user()->id;

      }

      $invoice->iid = Str::orderedUuid();
      $invoice->status = Invoice::STATUS_DRAFT;
      $invoice->invoice_number = self::generateInvoiceNumber();

    });

    static::updating(function ($invoice) {

      if (!isset($invoice->iid)) {

        $invoice->iid = Str::orderedUuid();

      }

    });
  }

}

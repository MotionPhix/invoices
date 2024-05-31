<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

  const STATUSES = [
    ['label' => 'Draft', 'value' => self::STATUS_DRAFT],
    ['label' => 'Paid', 'value' => self::STATUS_PAID],
    ['label' => 'Partial', 'value' => self::STATUS_PARTIAL],
    ['label' => 'Canceled', 'value' => self::STATUS_CANCELED],
  ];

  protected $fillable = [
    'user_id',
    'contact_id',
    'invoice_number',
    'invoice_date',
    'description',
    'currency',
    'status',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function contact()
  {
    return $this->belongsTo(Contact::class);
  }

  public function items()
  {
    return $this->hasMany(InvoiceItem::class);
  }

  public function subtotal(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->items->sum(function ($item) {
        return $item->quantity * $item->unit_price;
      })
    );
  }

  public function vatAmount(): Attribute
  {
    $settings = Settings::first();

    return Attribute::make(
      get: fn() => $this->subtotal * ($settings->vat_rate / 100)
    );
  }

  public function totalAmount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->subtotal + $this->vatAmount
    );
  }

  public static function generateInvoiceNumber()
  {
    $settings = Settings::first();

    $prefix = $settings->invoice_prefix;

    $suffix = $settings->invoice_suffix;

    $lastInvoice = self::orderBy('id', 'desc')->first();

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

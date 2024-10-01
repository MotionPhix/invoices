<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
  use HasFactory;

  const PAYMENT_CASH = 'cash';
  const PAYMENT_BANK = 'bank_transfer';
  const PAYMENT_CREDIT = 'credit_card';

  const STATUSES = [
    ['label' => 'Cash', 'value' => self::PAYMENT_CASH],
    ['label' => 'Bank Transfer', 'value' => self::PAYMENT_BANK],
    ['label' => 'Credit Card', 'value' => self::PAYMENT_CREDIT],
  ];

  protected $fillable = [
    'invoice_id',
    'amount',
    'payment_date',
    'payment_method',
    'transaction_reference',
    'status',
    // Fields for specific payment methods
    'bank_name',
    'account_number',
    'routing_number',
    'card_type',
    'last_four',
    'authorization_code',
    'paypal_email',
    'payer_id',
    // Cheque-specific fields
    'cheque_number',
    'bank_name',
    'cheque_date',
    'cheque_status', // e.g., 'pending', 'cleared', 'bounced'
  ];

  /**
   * Define a relationship with the Invoice model.
   * Each payment belongs to one invoice.
   */
  public function invoice()
  {
    return $this->belongsTo(Invoice::class);
  }
}

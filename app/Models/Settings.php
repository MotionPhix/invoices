<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Settings extends Model
{
  use HasFactory;

  const CURRENCIES = ['MWK','ZMW','ZAR','GBP', 'USD'];

  protected $fillable = [
    'invoice_prefix',
    'invoice_suffix',
    'invoice_start_number',
    'invoice_number_length',
    'currency',
    'vat_rate',
    'company_name',
    'company_email',
    'company_phone',
  ];

  public function address(): MorphOne
  {
    return $this->morphOne(Address::class, 'model');
  }

}
